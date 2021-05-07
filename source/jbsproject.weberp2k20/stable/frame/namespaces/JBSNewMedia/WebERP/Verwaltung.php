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

class Verwaltung {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseVarTrait;
	use osWFrame\BaseConnectionTrait;
	use VIS2\BaseUserTrait;
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
	private array $config=[];

	/**
	 * @var array
	 */
	private array $mitarbeiter=[];

	/**
	 * @var array
	 */
	private array $kunden_anreden=[];

	/**
	 * @var array
	 */
	private array $kunden_laender=[];

	/**
	 * @var array
	 */
	private array $kunden_konten=[];

	/**
	 * @var array
	 */
	private array $kunde_by_id=[];

	/**
	 * @var array
	 */
	private array $artikel_by_id=[];

	/**
	 * @var array
	 */
	private array $years=[];

	/**
	 * @var array
	 */
	private array $artikel_typen=[];

	/**
	 * @var array
	 */
	private array $artikel_mwst=[];

	/**
	 * @var array
	 */
	private array $kunden=[];

	/**
	 * @var array
	 */
	private array $artikel=[];

	/**
	 * @var array
	 */
	private array $artikel_all=[];

	/**
	 * Verwaltung constructor.
	 *
	 * @param int $mandant_id
	 */
	public function __construct(int $mandant_id=0) {
		if ($mandant_id>0) {
			$this->setMandantId($mandant_id);
			$this->load();
		}
	}

	/**
	 * @return int
	 */
	public static function getFutureYear():int {
		return date('Y')+1;
	}

	/**
	 * @return int
	 */
	public static function getBeginningYear():int {
		return 2000;
	}

	/**
	 * @return int
	 */
	public static function getPositionsMax():int {
		return 25;
	}

	/**
	 * @return int
	 */
	public static function getStringMaxLength():int {
		return 75;
	}

	/**
	 * @param string $str
	 * @param string $shortenchars
	 * @return string
	 */
	public function shortenString(string $str, string $shortenchars='...'):string {
		if (strlen($str)>$this->getStringMaxLength()) {
			return substr($str, 0, $this->getStringMaxLength()).' '.$shortenchars;
		}

		return $str;
	}

	/**
	 * @return array
	 */
	public function getYears():array {
		$this->years=[];
		for ($i=$this->getFutureYear(); $i>=$this->getBeginningYear(); $i--) {
			$this->years[$i]=$i;
		}

		return $this->years;
	}

	/**
	 * @param float $number
	 * @param string $currency
	 * @return string
	 */
	public function formatNumber(float $var, string $currency='€') {
		if (($currency!='')&&(substr($currency, 0, 1)!=' ')) {
			$currency=' '.$currency;
		}

		return osWFrame\Math::formatNumber($var).$currency;
	}

	/**
	 * @return bool
	 */
	public function load():bool {
		if ($this->getMandantId()==0) {
			return false;
		}

		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_config: WHERE mandant_id=:mandant_id:');
		$QselectData->bindTable(':table_weberp_config:', 'weberp_config');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		foreach ($QselectData->query() as $config) {
			switch ($config['config_type']) {
				case 'bool':
					$this->setBoolVar($config['config_name'], $config['config_value_bool']);
					break;
				case 'int':
					$this->setIntVar($config['config_name'], $config['config_value_int']);
					break;
				case 'float':
					$this->setFloatVar($config['config_name'], $config['config_value_float']);
					break;
				case 'text':
					$this->setStringVar($config['config_name'], $config['config_value_text']);
					break;
				case 'string':
				default:
					$this->setStringVar($config['config_name'], $config['config_value_string']);
					break;
			}
		}

		return true;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @param string $type
	 * @param int $mandant_id
	 * @return bool
	 */
	public function writeVar(string $key, $value, string $type, int $mandant_id=0):bool {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}

		$time=time();

		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT * FROM :table_weberp_config: WHERE mandant_id=:mandant_id: AND config_name=:config_name:');
		$QgetData->bindTable(':table_weberp_config:', 'weberp_config');
		$QgetData->bindInt(':mandant_id:', $mandant_id);
		$QgetData->bindString(':config_name:', $key);
		$QgetData->execute();
		if ($QgetData->rowCount()>1) {
			$QdeleteData=self::getConnection();
			$QdeleteData->prepare('DELETE FROM :table_weberp_config: WHERE mandant_id=:mandant_id: AND config_name=:config_name:');
			$QdeleteData->bindTable(':table_weberp_config:', 'weberp_config');
			$QdeleteData->bindInt(':mandant_id:', $mandant_id);
			$QdeleteData->bindString(':config_name:', $key);
			$QdeleteData->execute();
		}
		if ($QgetData->rowCount()==1) {
			$QgetData->fetch();
			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_weberp_config: SET :config_typename:=:config_value:, config_update_time=:config_update_time:, config_update_user_id=:config_update_user_id: WHERE config_id=:config_id:');
			$QupdateData->bindTable(':table_weberp_config:', 'weberp_config');
			$QupdateData->bindInt(':config_id:', $QgetData->getInt('config_id'));
			switch ($type) {
				case 'bool':
					$QupdateData->bindRaw(':config_typename:', 'config_value_bool');
					$QupdateData->bindBool(':config_value:', $value);
					break;
				case 'int':
					$QupdateData->bindRaw(':config_typename:', 'config_value_int');
					$QupdateData->bindInt(':config_value:', $value);
					break;
				case 'float':
					$QupdateData->bindRaw(':config_typename:', 'config_value_float');
					$QupdateData->bindFloat(':config_value:', $value);
					break;
				case 'text':
					$QupdateData->bindRaw(':config_typename:', 'config_value_text');
					$QupdateData->bindString(':config_value:', $value);
					break;
				case 'string':
				default:
					$QupdateData->bindRaw(':config_typename:', 'config_value_string');
					$QupdateData->bindString(':config_value:', $value);
					break;
			}
			$QupdateData->bindInt(':config_update_time:', $time);
			$QupdateData->bindInt(':config_update_user_id:', $this->getUserId());
			$QupdateData->execute();
		} else {
			$QinsertData=self::getConnection();
			$QinsertData->prepare('INSERT INTO :table_weberp_config: (mandant_id, config_name, config_description, :config_typename:, config_type, config_ispublic, config_create_time, config_create_user_id, config_update_time, config_update_user_id) VALUES (:mandant_id:, :config_name:, :config_description:, :config_value:, :config_type:, :config_ispublic:, :config_create_time:, :config_create_user_id:, :config_update_time:, :config_update_user_id:)');
			$QinsertData->bindTable(':table_weberp_config:', 'weberp_config');
			$QinsertData->bindInt(':mandant_id:', $mandant_id);
			$QinsertData->bindString(':config_name:', $key);
			$QinsertData->bindString(':config_description:', $key);
			switch ($type) {
				case 'bool':
					$QinsertData->bindRaw(':config_typename:', 'config_value_bool');
					$QinsertData->bindString(':config_value:', $value);
					$QinsertData->bindString(':config_type:', 'bool');
					break;
				case 'int':
					$QinsertData->bindRaw(':config_typename:', 'config_value_int');
					$QinsertData->bindInt(':config_value:', $value);
					$QinsertData->bindString(':config_type:', 'int');
					break;
				case 'float':
					$QinsertData->bindRaw(':config_typename:', 'config_value_float');
					$QinsertData->bindFloat(':config_value:', $value);
					$QinsertData->bindString(':config_type:', 'float');
					break;
				case 'text':
					$QinsertData->bindRaw(':config_typename:', 'config_value_text');
					$QinsertData->bindString(':config_value:', $value);
					$QinsertData->bindString(':config_type:', 'text');
					break;
				case 'string':
				default:
					$QinsertData->bindRaw(':config_typename:', 'config_value_string');
					$QinsertData->bindString(':config_value:', $value);
					$QinsertData->bindString(':config_type:', 'string');
					break;
			}
			$QinsertData->bindInt(':config_ispublic:', 1);
			$QinsertData->bindInt(':config_create_time:', $time);
			$QinsertData->bindInt(':config_create_user_id:', $this->getUserId());
			$QinsertData->bindInt(':config_update_time:', $time);
			$QinsertData->bindInt(':config_update_user_id:', $this->getUserId());
			$QinsertData->execute();
		}

		return true;
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @param int $mandant_id
	 * @return bool
	 */
	public function writeBoolVar(string $key, int $value, int $mandant_id=0):bool {
		return $this->writeVar($key, $value, 'bool', $mandant_id);
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @param int $mandant_id
	 * @return bool
	 */
	public function writeIntVar(string $key, int $value, int $mandant_id=0):bool {
		return $this->writeVar($key, $value, 'int', $mandant_id);
	}

	/**
	 * @param string $key
	 * @param float $value
	 * @param int $mandant_id
	 * @return bool
	 */
	public function writeFloatVar(string $key, float $value, int $mandant_id=0):bool {
		return $this->writeVar($key, $value, 'float', $mandant_id);
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param int $mandant_id
	 * @return bool
	 */
	public function writeStringVar(string $key, string $value, int $mandant_id=0):bool {
		return $this->writeVar($key, $value, 'string', $mandant_id);
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param int $mandant_id
	 * @return bool
	 */
	public function writeTextVar(string $key, string $value, int $mandant_id=0):bool {
		return $this->writeVar($key, $value, 'text', $mandant_id);
	}

	/**
	 * @return array
	 */
	public function getProfiles():array {
		$profiles=[];
		$path=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR;
		foreach (glob($path.'*') as $dir) {
			$json_file=$path.basename($dir).DIRECTORY_SEPARATOR.'profile.json';
			if (osWFrame\Filesystem::existsFile($json_file)) {
				$json=json_decode(file_get_contents($json_file), true);
				if (isset($json['title'])) {
					$profiles[basename($dir)]=$json['title'];
				}
			}
		}

		return $profiles;
	}

	/**
	 * @param int $mandant_id
	 * @return array
	 */
	public function getMitarbeiter(int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		if ($this->mitarbeiter==[]) {
			$this->mitarbeiter=[];
			$QselectData=self::getConnection();
			$QselectData->prepare('SELECT * FROM :table_weberp_mitarbeiter: WHERE mandant_id=:mandant_id: ORDER BY mitarbeiter_nachname ASC, mitarbeiter_vorname ASC, mitarbeiter_nr DESC');
			$QselectData->bindTable(':table_weberp_mitarbeiter:', 'weberp_mitarbeiter');
			$QselectData->bindInt(':mandant_id:', $mandant_id);
			foreach ($QselectData->query() as $mitarbeiter) {
				$this->mitarbeiter[$mitarbeiter['mitarbeiter_id']]=$mitarbeiter['mitarbeiter_nachname'].' '.$mitarbeiter['mitarbeiter_vorname'].' ('.$mitarbeiter['mitarbeiter_nr'].')';
			}
		}

		return $this->mitarbeiter;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $onlypublic
	 * @param int $mandant_id
	 * @return array
	 */
	public function getKundenAnreden(bool $onlypublic=true, string $key='anrede_id', string $value='anrede_titel', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->kunden_anreden=[];
		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde_anrede: WHERE mandant_id=:mandant_id: AND anrede_ispublic=:anrede_ispublic: ORDER BY :value: ASC');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde_anrede: WHERE mandant_id=:mandant_id: ORDER BY :value: ASC');
		}
		$QselectData->bindTable(':table_weberp_kunde_anrede:', 'weberp_kunde_anrede');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':anrede_ispublic:', 1);
		$QselectData->bindRaw(':value:', $value);
		foreach ($QselectData->query() as $kundeanrede) {
			$this->kunden_anreden[$kundeanrede[$key]]=$kundeanrede[$value];
		}

		return $this->kunden_anreden;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $onlypublic
	 * @param int $mandant_id
	 * @return array
	 */
	public function getKundenLaender(bool $onlypublic=true, string $key='land_id', string $value='land_titel', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->kunden_laender=[];
		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde_land: WHERE mandant_id=:mandant_id: AND land_ispublic=:land_ispublic: ORDER BY :value: ASC');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde_land: WHERE mandant_id=:mandant_id: ORDER BY :value: ASC');
		}
		$QselectData->bindTable(':table_weberp_kunde_land:', 'weberp_kunde_land');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':land_ispublic:', 1);
		$QselectData->bindRaw(':value:', $value);
		foreach ($QselectData->query() as $kundeanrede) {
			$this->kunden_laender[$kundeanrede[$key]]=$kundeanrede[$value];
		}

		return $this->kunden_laender;
	}

	/**
	 * @param bool $onlypublic
	 * @param string $key
	 * @param string $value
	 * @param string $value1
	 * @param string $value2
	 * @param int $mandant_id
	 * @return array
	 */
	public function getKundenKonten(bool $onlypublic=true, string $key='konto_id', string $value='konto_iban', string $value1='kunde_firma', string $value2='kunde_nr', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->kunden_konten=[];
		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde_konto: AS kk LEFT JOIN :table_weberp_kunde: AS k ON (k.kunde_id=kk.kunde_id AND k.mandant_id=kk.mandant_id) WHERE kk.mandant_id=:mandant_id: AND kk.konto_ispublic=:konto_ispublic: ORDER BY :value: ASC');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde_konto: AS kk LEFT JOIN :table_weberp_kunde: AS k ON (k.kunde_id=kk.kunde_id AND k.mandant_id=kk.mandant_id) WHERE kk.mandant_id=:mandant_id: ORDER BY :value: ASC');
		}
		$QselectData->bindTable(':table_weberp_kunde_konto:', 'weberp_kunde_konto');
		$QselectData->bindTable(':table_weberp_kunde:', 'weberp_kunde');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':konto_ispublic:', 1);
		$QselectData->bindRaw(':value:', $value);
		foreach ($QselectData->query() as $kundekonto) {
			$this->kunden_konten[$kundekonto[$key]]=$kundekonto[$value].', '.$kundekonto[$value1].' ['.$kundekonto[$value2].']';
		}

		return $this->kunden_konten;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $onlypublic
	 * @param int $mandant_id
	 * @return array
	 */
	public function getArtikelTypen(bool $onlypublic=true, string $key='typ_id', string $value='typ_titel', string $order='typ_titel', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->artikel_typen=[];
		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel_typ: WHERE mandant_id=:mandant_id: AND typ_ispublic=:typ_ispublic: ORDER BY :order: ASC');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel_typ: WHERE mandant_id=:mandant_id: ORDER BY :order: ASC');
		}
		$QselectData->bindTable(':table_weberp_artikel_typ:', 'weberp_artikel_typ');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':typ_ispublic:', 1);
		$QselectData->bindRaw(':order:', $order);
		foreach ($QselectData->query() as $artikel_typ) {
			if ($value=='') {
				$this->artikel_typen[$artikel_typ[$key]]=$artikel_typ;
			} else {
				$this->artikel_typen[$artikel_typ[$key]]=$artikel_typ[$value];
			}
		}

		return $this->artikel_typen;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $onlypublic
	 * @param int $mandant_id
	 * @return array
	 */
	public function getArtikelMwSt(bool $onlypublic=true, string $key='mwst_id', string $value='mwst_titel', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->artikel_mwst=[];
		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel_mwst: WHERE mandant_id=:mandant_id: AND mwst_ispublic=:mwst_ispublic: ORDER BY :value: ASC');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel_mwst: WHERE mandant_id=:mandant_id: ORDER BY :value: ASC');
		}
		$QselectData->bindTable(':table_weberp_artikel_mwst:', 'weberp_artikel_mwst');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':mwst_ispublic:', 1);
		$QselectData->bindRaw(':value:', $value);
		foreach ($QselectData->query() as $artikel_mwst) {
			$this->artikel_mwst[$artikel_mwst[$key]]=$artikel_mwst[$value];
		}

		return $this->artikel_mwst;
	}

	/**
	 * @param bool $onlypublic
	 * @param string $key
	 * @param string $value
	 * @param string $sort
	 * @param string $order
	 * @param int $mandant_id
	 * @return array
	 */
	public function getKunden(bool $onlypublic=true, string $key='kunde_id', string $value='kunde_name', string $sort='kunde_nr', string $order='desc', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->kunden=[];
		$order=strtoupper($order);
		if (!in_array($order, ['ASC', 'DESC'])) {
			$order='DESC';
		}

		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: AND kunde_ispublic=:kunde_ispublic: ORDER BY :sort: :order:');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: ORDER BY :sort: :order:');
		}
		$QselectData->bindTable(':table_weberp_kunde:', 'weberp_kunde');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':kunde_ispublic:', 1);
		$QselectData->bindRaw(':sort:', $sort);
		$QselectData->bindRaw(':order:', $order);
		foreach ($QselectData->query() as $kunde) {
			$_name='';
			$_name.=$kunde['kunde_nr'];
			if (strlen($kunde['kunde_firma'])>0) {
				$_name.=' - '.$kunde['kunde_firma'];
			} else {
				$_name.=' - '.$kunde['kunde_anrede'].' '.$kunde['kunde_titel'].' '.$kunde['kunde_vorname'].' '.$kunde['kunde_nachname'];
			}
			$kunde['kunde_name']=$this->shortenString(str_replace('  ', ' ', $_name));

			$this->kunden[$kunde[$key]]=$kunde[$value];
		}

		return $this->kunden;
	}

	/**
	 * @param bool $onlypublic
	 * @param string $key
	 * @param string $value
	 * @param string $sort
	 * @param string $order
	 * @param int $mandant_id
	 * @return array
	 */
	public function getArtikel(bool $onlypublic=true, string $key='artikel_id', string $value='artikel_name', string $sort='artikel_kurz', string $order='desc', int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		$this->artikel=[];
		$order=strtoupper($order);
		if (!in_array($order, ['ASC', 'DESC'])) {
			$order='DESC';
		}
		$QselectData=self::getConnection();
		if ($onlypublic===true) {
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel: WHERE mandant_id=:mandant_id: AND artikel_ispublic=:artikel_ispublic: ORDER BY :sort: :order:');
		} else {
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel: WHERE mandant_id=:mandant_id: ORDER BY :sort: :order:');
		}
		$QselectData->bindTable(':table_weberp_artikel:', 'weberp_artikel');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':artikel_ispublic:', 1);
		$QselectData->bindRaw(':sort:', $sort);
		$QselectData->bindRaw(':order:', $order);
		foreach ($QselectData->query() as $artikel) {
			$_name='';
			$_name.=$artikel['artikel_kurz'];
			$_name.=' - '.$artikel['artikel_beschreibung'].' '.$this->formatNumber($artikel['artikel_preis'], 'Euro');
			$artikel['artikel_name']=$this->shortenString(str_replace('  ', ' ', $_name));

			$this->artikel[$artikel[$key]]=$artikel[$value];
		}

		return $this->artikel;
	}

	/**
	 * @param int $kunde_id
	 * @param int $mandant_id
	 * @return array
	 */
	public function getKundeById(int $kunde_id, int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		if (!isset($this->kunde_by_id[$kunde_id])) {
			$this->kunde_by_id[$kunde_id]=[];
			$QselectData=self::getConnection();
			$QselectData->prepare('SELECT * FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: AND kunde_id=:kunde_id:');
			$QselectData->bindTable(':table_weberp_kunde:', 'weberp_kunde');
			$QselectData->bindInt(':mandant_id:', $mandant_id);
			$QselectData->bindInt(':kunde_id:', $kunde_id);
			$QselectData->execute();
			if ($QselectData->rowCount()==1) {
				$QselectData->fetch();
				$this->kunde_by_id[$kunde_id]=$QselectData->result;
			}

		}

		return $this->kunde_by_id[$kunde_id];
	}

	/**
	 * @param int $artikel_id
	 * @param int $mandant_id
	 * @return array
	 */
	public function getArtikelById(int $artikel_id, int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}
		if (!isset($this->artikel_by_id[$artikel_id])) {
			$this->artikel_by_id[$artikel_id]=[];
			$QselectData=self::getConnection();
			$QselectData->prepare('SELECT * FROM :table_weberp_artikel: WHERE mandant_id=:mandant_id: AND artikel_id=:artikel_id:');
			$QselectData->bindTable(':table_weberp_artikel:', 'weberp_artikel');
			$QselectData->bindInt(':mandant_id:', $mandant_id);
			$QselectData->bindInt(':artikel_id:', $artikel_id);
			$QselectData->execute();
			if ($QselectData->rowCount()==1) {
				$QselectData->fetch();
				$this->artikel_by_id[$artikel_id]=$QselectData->result;
			}

		}

		return $this->artikel_by_id[$artikel_id];
	}

	/**
	 * @param int $artikel_id
	 * @param int $mandant_id
	 * @return array
	 */
	public function getAngebotIdsByArtikelId(int $artikel_id, int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}

		$angebot_ids=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_angebot_position: WHERE mandant_id=:mandant_id: AND position_artikel_id=:position_artikel_id: GROUP BY angebot_id');
		$QselectData->bindTable(':table_weberp_angebot_position:', 'weberp_angebot_position');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':position_artikel_id:', $artikel_id);
		foreach ($QselectData->query() as $angebot) {
			$angebot_ids[]=$angebot['angebot_id'];
		}

		return $angebot_ids;
	}

	/**
	 * @param int $artikel_id
	 * @param int $mandant_id
	 * @return array
	 */
	public function getRechnungIdsByArtikelId(int $artikel_id, int $mandant_id=0):array {
		if ($mandant_id==0) {
			$mandant_id=$this->getMandantId();
		}

		$rechnung_ids=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung_position: WHERE mandant_id=:mandant_id: AND position_artikel_id=:position_artikel_id: GROUP BY rechnung_id');
		$QselectData->bindTable(':table_weberp_rechnung_position:', 'weberp_rechnung_position');
		$QselectData->bindInt(':mandant_id:', $mandant_id);
		$QselectData->bindInt(':position_artikel_id:', $artikel_id);
		foreach ($QselectData->query() as $rechnung) {
			$rechnung_ids[]=$rechnung['rechnung_id'];
		}

		return $rechnung_ids;
	}

	/**
	 * @return array
	 */
	public function createRechnungen():array {
		$data=[];

		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung_cron: WHERE mandant_id=:mandant_id: AND cron_ispublic=:cron_ispublic: ORDER BY cron_date ASC');
		$QselectData->bindTable(':table_weberp_rechnung_cron:', 'weberp_rechnung_cron');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':cron_ispublic:', 1);
		foreach ($QselectData->query() as $rechnung_cron) {
			if (!isset($data[$rechnung_cron['kunde_id']])) {
				$data[$rechnung_cron['kunde_id']]=[];
			}

			$text=[];
			for ($y=substr($rechnung_cron['cron_date'], 0, 4); $y<=date('Y'); $y++) {
				$text['Y-2']=date('y', mktime(12, 0, 0, 1, 1, $y-2));
				$text['YY-2']=date('Y', mktime(12, 0, 0, 1, 1, $y-2));
				$text['Y-1']=date('y', mktime(12, 0, 0, 1, 1, $y-1));
				$text['YY-1']=date('Y', mktime(12, 0, 0, 1, 1, $y-1));
				$text['Y']=date('y', mktime(12, 0, 0, 1, 1, $y));
				$text['YY']=date('Y', mktime(12, 0, 0, 1, 1, $y));
				$text['Y+1']=date('y', mktime(12, 0, 0, 1, 1, $y+1));
				$text['YY+1']=date('Y', mktime(12, 0, 0, 1, 1, $y+1));
				$text['Y+2']=date('y', mktime(12, 0, 0, 1, 1, $y+2));
				$text['YY+2']=date('Y', mktime(12, 0, 0, 1, 1, $y+2));
				for ($m=1; $m<=12; $m++) {
					$text['M-12']=date('m', mktime(12, 0, 0, $m-12, 1, $y));
					$text['M-11']=date('m', mktime(12, 0, 0, $m-11, 1, $y));
					$text['M-10']=date('m', mktime(12, 0, 0, $m-10, 1, $y));
					$text['M-9']=date('m', mktime(12, 0, 0, $m-9, 1, $y));
					$text['M-8']=date('m', mktime(12, 0, 0, $m-8, 1, $y));
					$text['M-7']=date('m', mktime(12, 0, 0, $m-7, 1, $y));
					$text['M-6']=date('m', mktime(12, 0, 0, $m-6, 1, $y));
					$text['M-5']=date('m', mktime(12, 0, 0, $m-5, 1, $y));
					$text['M-4']=date('m', mktime(12, 0, 0, $m-4, 1, $y));
					$text['M-3']=date('m', mktime(12, 0, 0, $m-3, 1, $y));
					$text['M-2']=date('m', mktime(12, 0, 0, $m-2, 1, $y));
					$text['M-1']=date('m', mktime(12, 0, 0, $m-1, 1, $y));
					$text['M']=date('m', mktime(12, 0, 0, $m, 1, $y));
					$text['M+1']=date('m', mktime(12, 0, 0, $m+1, 1, $y));
					$text['M+2']=date('m', mktime(12, 0, 0, $m+2, 1, $y));
					$text['M+3']=date('m', mktime(12, 0, 0, $m+3, 1, $y));
					$text['M+4']=date('m', mktime(12, 0, 0, $m+4, 1, $y));
					$text['M+5']=date('m', mktime(12, 0, 0, $m+5, 1, $y));
					$text['M+6']=date('m', mktime(12, 0, 0, $m+6, 1, $y));
					$text['M+7']=date('m', mktime(12, 0, 0, $m+7, 1, $y));
					$text['M+8']=date('m', mktime(12, 0, 0, $m+8, 1, $y));
					$text['M+9']=date('m', mktime(12, 0, 0, $m+9, 1, $y));
					$text['M+10']=date('m', mktime(12, 0, 0, $m+10, 1, $y));
					$text['M+11']=date('m', mktime(12, 0, 0, $m+11, 1, $y));
					$text['M+12']=date('m', mktime(12, 0, 0, $m+12, 1, $y));

					$text['T-12']=date('t', mktime(12, 0, 0, $m-12, 1, $y));
					$text['T-11']=date('t', mktime(12, 0, 0, $m-11, 1, $y));
					$text['T-10']=date('t', mktime(12, 0, 0, $m-10, 1, $y));
					$text['T-9']=date('t', mktime(12, 0, 0, $m-9, 1, $y));
					$text['T-8']=date('t', mktime(12, 0, 0, $m-8, 1, $y));
					$text['T-7']=date('t', mktime(12, 0, 0, $m-7, 1, $y));
					$text['T-6']=date('t', mktime(12, 0, 0, $m-6, 1, $y));
					$text['T-5']=date('t', mktime(12, 0, 0, $m-5, 1, $y));
					$text['T-4']=date('t', mktime(12, 0, 0, $m-4, 1, $y));
					$text['T-3']=date('t', mktime(12, 0, 0, $m-3, 1, $y));
					$text['T-2']=date('t', mktime(12, 0, 0, $m-2, 1, $y));
					$text['T-1']=date('t', mktime(12, 0, 0, $m-1, 1, $y));
					$text['T']=date('t', mktime(12, 0, 0, $m, 1, $y));
					$text['T+1']=date('t', mktime(12, 0, 0, $m+1, 1, $y));
					$text['T+2']=date('t', mktime(12, 0, 0, $m+2, 1, $y));
					$text['T+3']=date('t', mktime(12, 0, 0, $m+3, 1, $y));
					$text['T+4']=date('t', mktime(12, 0, 0, $m+4, 1, $y));
					$text['T+5']=date('t', mktime(12, 0, 0, $m+5, 1, $y));
					$text['T+6']=date('t', mktime(12, 0, 0, $m+6, 1, $y));
					$text['T+7']=date('t', mktime(12, 0, 0, $m+7, 1, $y));
					$text['T+8']=date('t', mktime(12, 0, 0, $m+8, 1, $y));
					$text['T+9']=date('t', mktime(12, 0, 0, $m+9, 1, $y));
					$text['T+10']=date('t', mktime(12, 0, 0, $m+10, 1, $y));
					$text['T+11']=date('t', mktime(12, 0, 0, $m+11, 1, $y));
					$text['T+12']=date('t', mktime(12, 0, 0, $m+12, 1, $y));

					$m=sprintf('%02d', $m);
					if ((($y.sprintf('%02d', $m))>=(substr($rechnung_cron['cron_date'], 0, 6)))&&(($y.$m<=date('Ym')))) {
						if (substr($rechnung_cron['cron_months'], ($m-1), 1)=='1') {
							if (!isset($data[$rechnung_cron['kunde_id']][$y])) {
								$data[$rechnung_cron['kunde_id']][$y]=[];
							}
							if (!isset($data[$rechnung_cron['kunde_id']][$y][$m])) {
								$data[$rechnung_cron['kunde_id']][$y][$m]=[];
							}

							$result=$rechnung_cron;

							$result['cron_artikel_1_zusatz']=osWFrame\StringFunctions::parseTextWithVars($rechnung_cron['cron_artikel_1_zusatz'], $text);
							$result['cron_artikel_1_beschreibung']=osWFrame\StringFunctions::parseTextWithVars($rechnung_cron['cron_artikel_1_beschreibung'], $text);
							$result['cron_leistung_von']=osWFrame\StringFunctions::parseTextWithVars($rechnung_cron['cron_leistung_von'], $text);
							$result['cron_leistung_bis']=osWFrame\StringFunctions::parseTextWithVars($rechnung_cron['cron_leistung_bis'], $text);
							$data[$rechnung_cron['kunde_id']][$y][$m][$rechnung_cron['cron_id']]=$result;
						}
					}
				}
			}
		}

		foreach ($data as $kunde_id=>$rechnung_data_year) {
			foreach ($rechnung_data_year as $year=>$rechnung_data_month) {
				foreach ($rechnung_data_month as $month=>$rechnung_data) {
					foreach ($rechnung_data as $id=>$element) {
						$QselectData=self::getConnection();
						$QselectData->prepare('SELECT r.rechnung_id FROM :table_weberp_rechnung_position: AS p LEFT JOIN :table_weberp_rechnung: AS r ON (r.rechnung_id=p.rechnung_id) WHERE p.mandant_id=:mandant_id: AND p.position_artikel_cron=:position_artikel_cron:');
						$QselectData->bindTable(':table_weberp_rechnung_position:', 'weberp_rechnung_position');
						$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
						$QselectData->bindInt(':mandant_id:', $this->getMandantId());
						$QselectData->bindInt(':kunde_id:', $kunde_id);
						$QselectData->bindInt(':position_artikel_cron:', $year.$month.$id);
						$QselectData->execute();
						if ($QselectData->rowCount()>0) {
							unset($data[$kunde_id][$year][$month][$id]);
							if ($data[$kunde_id][$year][$month]==[]) {
								unset($data[$kunde_id][$year][$month]);
								if ($data[$kunde_id][$year]==[]) {
									unset($data[$kunde_id][$year]);
									if ($data[$kunde_id]==[]) {
										unset($data[$kunde_id]);
									}
								}
							}
						}
					}
				}
			}
		}

		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_stunde: WHERE mandant_id=:mandant_id: AND stunde_abrechnen=:stunde_abrechnen: AND stunde_abgerechnet=:stunde_abgerechnet: ORDER BY stunde_datum ASC');
		$QselectData->bindTable(':table_weberp_stunde:', 'weberp_stunde');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':stunde_abrechnen:', 1);
		$QselectData->bindInt(':stunde_abgerechnet:', 0);
		foreach ($QselectData->query() as $weberp_stunde) {
			if (!isset($data[$weberp_stunde['kunde_id']])) {
				$data[$weberp_stunde['kunde_id']]=[];
			}

			$weberp_stunde['cron_leistung_von']=$weberp_stunde['stunde_datum'];
			$weberp_stunde['cron_leistung_bis']=$weberp_stunde['stunde_datum'];

			$artikel=self::getArtikelById($weberp_stunde['artikel_id']);

			$weberp_stunde['cron_artikel_1_anzahl']=$weberp_stunde['artikel_anzahl'];
			$weberp_stunde['cron_artikel_1_id']=$artikel['artikel_id'];
			$weberp_stunde['cron_artikel_1_zusatz']=$weberp_stunde['artikel_zusatz'];
			$weberp_stunde['cron_artikel_1_nr']=$artikel['artikel_nr'];
			$weberp_stunde['cron_artikel_1_kurz']=$artikel['artikel_kurz'];
			$weberp_stunde['cron_artikel_1_beschreibung']=$artikel['artikel_beschreibung'];
			$weberp_stunde['cron_artikel_1_beschreibung_ausblenden']=$artikel['artikel_beschreibung_ausblenden'];
			$weberp_stunde['cron_artikel_1_preis']=$artikel['artikel_preis'];
			$weberp_stunde['cron_artikel_1_typ']=$artikel['artikel_typ'];
			$weberp_stunde['cron_artikel_1_mwst']=$artikel['artikel_mwst'];

			$data[$weberp_stunde['kunde_id']][date('Y')][date('m')]['s'.$weberp_stunde['stunde_id']]=$weberp_stunde;
		}

		$time=time();
		$cron_rechnung=[];

		foreach ($data as $kunde_id=>$rechnung_data_year) {
			$QselectKunde=self::getConnection();
			$QselectKunde->prepare('SELECT * FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: AND kunde_id=:kunde_id:');
			$QselectKunde->bindTable(':table_weberp_kunde:', 'weberp_kunde');
			$QselectKunde->bindInt(':mandant_id:', $this->getMandantId());
			$QselectKunde->bindInt(':kunde_id:', $kunde_id);
			$QselectKunde->execute();
			if ($QselectKunde->rowCount()==1) {
				$QselectKunde->fetch();
				foreach ($rechnung_data_year as $year=>$rechnung_data_month) {
					$rechnung=[];
					$rechnung['kunde_id']=$kunde_id;
					$rechnung['kunde_data']=$QselectKunde->result;
					$rechnung['rechnung_nr']='';
					$rechnung['leistung_von']=99999999;
					$rechnung['leistung_bis']=0;
					$rechnung['artikel']=[];
					foreach ($rechnung_data_month as $month=>$rechnung_data) {
						foreach ($rechnung_data as $id=>$element) {
							$element['cron']=$year.$month.$id;
							if ($element['cron_leistung_von']<$rechnung['leistung_von']) {
								$rechnung['leistung_von']=$element['cron_leistung_von'];
							}
							if ($element['cron_leistung_bis']>$rechnung['leistung_bis']) {
								$rechnung['leistung_bis']=$element['cron_leistung_bis'];
							}
							$rechnung['artikel'][]=$element;
						}
					}
					$QselectRechnung=self::getConnection();
					$QselectRechnung->prepare('SELECT rechnung_nr FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_nr>:rechnung_nr: ORDER BY rechnung_id DESC LIMIT 1');
					$QselectRechnung->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
					$QselectRechnung->bindInt(':mandant_id:', $this->getMandantId());
					$QselectRechnung->bindInt(':rechnung_nr:', date('y').'000');
					$QselectRechnung->execute();
					if ($QselectRechnung->rowCount()==1) {
						$QselectRechnung->fetch();
						$rechnung['rechnung_nr']=$QselectRechnung->result['rechnung_nr'];
						$rechnung['rechnung_nr']=$rechnung['rechnung_nr']+1;
					} else {
						$rechnung['rechnung_nr']=date('y').'001';
					}

					$rechnung_mysql=[];
					$rechnung_mysql['rechnung_nr']=$rechnung['rechnung_nr'];
					$rechnung_mysql['kunde_id']=$rechnung['kunde_id'];
					$rechnung_mysql['rechnung_kunde_nr']=$rechnung['kunde_data']['kunde_nr'];
					$rechnung_mysql['rechnung_kunde_gewerblich']=$rechnung['kunde_data']['kunde_gewerblich'];
					$rechnung_mysql['rechnung_kunde_firma_anrede']=$rechnung['kunde_data']['kunde_firma_anrede'];
					$rechnung_mysql['rechnung_kunde_firma']=$rechnung['kunde_data']['kunde_firma'];
					$rechnung_mysql['rechnung_kunde_firma2']=$rechnung['kunde_data']['kunde_firma2'];
					$rechnung_mysql['rechnung_kunde_rechungsasp']=$rechnung['kunde_data']['kunde_rechungsasp'];
					$rechnung_mysql['rechnung_kunde_anrede']=$rechnung['kunde_data']['kunde_anrede'];
					$rechnung_mysql['rechnung_kunde_titel']=$rechnung['kunde_data']['kunde_titel'];
					$rechnung_mysql['rechnung_kunde_vorname']=$rechnung['kunde_data']['kunde_vorname'];
					$rechnung_mysql['rechnung_kunde_nachname']=$rechnung['kunde_data']['kunde_nachname'];
					$rechnung_mysql['rechnung_kunde_email']=$rechnung['kunde_data']['kunde_email'];
					$rechnung_mysql['rechnung_kunde_strasse']=$rechnung['kunde_data']['kunde_strasse'];
					$rechnung_mysql['rechnung_kunde_land']=$rechnung['kunde_data']['kunde_land'];
					$rechnung_mysql['rechnung_kunde_plz']=$rechnung['kunde_data']['kunde_plz'];
					$rechnung_mysql['rechnung_kunde_ort']=$rechnung['kunde_data']['kunde_ort'];
					$rechnung_mysql['rechnung_kunde_telefon']=$rechnung['kunde_data']['kunde_telefon'];
					$rechnung_mysql['rechnung_kunde_fax']=$rechnung['kunde_data']['kunde_fax'];
					$rechnung_mysql['rechnung_kunde_mobil']=$rechnung['kunde_data']['kunde_mobil'];
					$rechnung_mysql['rechnung_kunde_homepage']=$rechnung['kunde_data']['kunde_homepage'];
					$rechnung_mysql['rechnung_datum']=date('Ymd');
					$rechnung_mysql['rechnung_leistung_von']=$rechnung['leistung_von'];
					$rechnung_mysql['rechnung_leistung_bis']=$rechnung['leistung_bis'];
					$rechnung_mysql['rechnung_storniert']=0;
					$rechnung_mysql['rechnung_bezahlt']=0;
					$rechnung_mysql['rechnung_gesendet']=0;

					$position_mysql=[];
					foreach ($rechnung['artikel'] as $id=>$artikel) {
						$id++;

						$position_mysql[$id]['position_pos']=$id;
						$position_mysql[$id]['position_artikel_anzahl']=$artikel['cron_artikel_1_anzahl'];
						$position_mysql[$id]['position_artikel_id']=$artikel['cron_artikel_1_id'];
						$position_mysql[$id]['position_artikel_cron']=$artikel['cron'];
						$position_mysql[$id]['position_artikel_zusatz']=$artikel['cron_artikel_1_zusatz'];
						$position_mysql[$id]['position_artikel_nr']=$artikel['cron_artikel_1_nr'];
						$position_mysql[$id]['position_artikel_kurz']=$artikel['cron_artikel_1_kurz'];
						$position_mysql[$id]['position_artikel_beschreibung']=$artikel['cron_artikel_1_beschreibung'];
						$position_mysql[$id]['position_artikel_beschreibung_ausblenden']=$artikel['cron_artikel_1_beschreibung_ausblenden'];
						$position_mysql[$id]['position_artikel_preis']=$artikel['cron_artikel_1_preis'];
						$position_mysql[$id]['position_artikel_typ']=$artikel['cron_artikel_1_typ'];
						$position_mysql[$id]['position_artikel_mwst']=$artikel['cron_artikel_1_mwst'];
						$position_mysql[$id]['position_create_time']=$time;
						$position_mysql[$id]['position_create_user_id']=$this->getUserId();
						$position_mysql[$id]['position_update_time']=$time;
						$position_mysql[$id]['position_update_user_id']=$this->getUserId();

						$rechnung['rechnung_gesamt_netto']=round(($artikel['cron_artikel_1_anzahl']*$artikel['cron_artikel_1_preis']), 2);
						$rechnung['rechnung_gesamt_mwst']=round($rechnung['rechnung_gesamt_netto']*((100+$artikel['cron_artikel_1_mwst'])/100), 2)-$rechnung['rechnung_gesamt_netto'];
						$rechnung['rechnung_gesamt_brutto']=$rechnung['rechnung_gesamt_netto']+$rechnung['rechnung_gesamt_mwst'];

						$rechnung_mysql['rechnung_gesamt_netto']=$rechnung_mysql['rechnung_gesamt_netto']+$rechnung['rechnung_gesamt_netto'];
						$rechnung_mysql['rechnung_gesamt_brutto']=$rechnung_mysql['rechnung_gesamt_brutto']+$rechnung['rechnung_gesamt_brutto'];
						$rechnung_mysql['rechnung_gesamt_mwst']=$rechnung_mysql['rechnung_gesamt_mwst']+$rechnung['rechnung_gesamt_mwst'];
					}

					$rechnung_mysql['rechnung_gesamt_netto']=str_replace(',', '.', $rechnung_mysql['rechnung_gesamt_netto']);
					$rechnung_mysql['rechnung_gesamt_brutto']=str_replace(',', '.', $rechnung_mysql['rechnung_gesamt_brutto']);
					$rechnung_mysql['rechnung_gesamt_mwst']=str_replace(',', '.', $rechnung_mysql['rechnung_gesamt_mwst']);

					$cron_rechnung[]=['kunde_nr'=>$rechnung['kunde_data']['kunde_nr'], 'rechnung_nr'=>$rechnung['rechnung_nr'], 'rechnung_gesamt_netto'=>$rechnung_mysql['rechnung_gesamt_netto'], 'rechnung_gesamt_brutto'=>$rechnung_mysql['rechnung_gesamt_brutto'],];

					$rechnung_mysql['rechnung_create_time']=$time;
					$rechnung_mysql['rechnung_create_user_id']=$this->getUserId();
					$rechnung_mysql['rechnung_update_time']=$time;
					$rechnung_mysql['rechnung_update_user_id']=$this->getUserId();
					$rechnung_mysql['mandant_id']=$this->getMandantId();

					$left=[];
					foreach ($rechnung_mysql as $key=>$value) {
						$left[]=$key;
						$rechnung_mysql[$key]=$this->getConnection()->escapeString($value);
					}

					$QinsertRechnung=self::getConnection();
					$QinsertRechnung->prepare('INSERT INTO :table_weberp_rechnung: (:left:) VALUES (:right:)');
					$QinsertRechnung->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
					$QinsertRechnung->bindRaw(':left:', implode(', ', $left));
					$QinsertRechnung->bindRaw(':right:', implode(', ', $rechnung_mysql));
					$QinsertRechnung->execute();
					$rechnung_id=$QinsertRechnung->lastInsertId();

					foreach ($position_mysql as $position) {
						$position['rechnung_id']=$rechnung_id;
						$position['mandant_id']=$this->getMandantId();
						$left=[];
						foreach ($position as $key=>$value) {
							$left[]=$key;
							$position[$key]=$this->getConnection()->escapeString($value);
						}

						$QinsertPosition=self::getConnection();
						$QinsertPosition->prepare('INSERT INTO :table_weberp_rechnung_position: (:left:) VALUES (:right:)');
						$QinsertPosition->bindTable(':table_weberp_rechnung_position:', 'weberp_rechnung_position');
						$QinsertPosition->bindRaw(':left:', implode(', ', $left));
						$QinsertPosition->bindRaw(':right:', implode(', ', $position));
						$QinsertPosition->execute();
					}

					foreach ($rechnung['artikel'] as $id=>$artikel) {
						if (isset($artikel['stunde_id'])) {
							$QupdateStunde=self::getConnection();
							$QupdateStunde->prepare('UPDATE :table_weberp_stunde: SET rechnung_nr=:rechnung_nr:, rechnung_id=:rechnung_id:, stunde_abgerechnet=:stunde_abgerechnet: WHERE mandant_id=:mandant_id: AND stunde_id=:stunde_id:');
							$QupdateStunde->bindTable(':table_weberp_stunde:', 'weberp_stunde');
							$QupdateStunde->bindRaw(':left:', implode(', ', $left));
							$QupdateStunde->bindInt(':rechnung_nr:', $rechnung['rechnung_nr']);
							$QupdateStunde->bindInt(':rechnung_id:', $QinsertRechnung->nextId());
							$QupdateStunde->bindInt(':stunde_abgerechnet:', 1);
							$QupdateStunde->bindInt(':mandant_id:', $this->getMandantId());
							$QupdateStunde->bindInt(':stunde_id:', $artikel['stunde_id']);
							$QupdateStunde->execute();
						}
					}
				}
			}

		}

		return $cron_rechnung;
	}

	/**
	 * @param int $days
	 * @return int
	 */
	public static function getSepaTimeStamp(int $days=10):int {
		for ($i=$days; $i>0; $i--) {
			$ts=mktime(date('H'), date('i'), date('s'), date('n'), $i, date('Y'));
			if (\JBSNewMedia\Core\Kalender::isWorkingDay(date('n', $ts), date('j', $ts), date('Y', $ts))===true) {
				break;
			}
		}

		return $ts;
	}

	/**
	 * @return array
	 */
	public function getSepaEingang():array {
		$ts=self::getSepaTimeStamp();

		$data=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung: AS r INNER JOIN :table_weberp_kunde: AS k ON (k.kunde_id=r.kunde_id) INNER JOIN :table_weberp_kunde_konto: AS kk ON (kk.kunde_id=r.kunde_id) INNER JOIN :table_weberp_kunde_sepa: AS ks ON (ks.konto_id=kk.konto_id) WHERE r.mandant_id=:mandant_id: AND r.rechnung_bezahlt=:rechnung_bezahlt: AND r.rechnung_storniert=:rechnung_storniert: AND kk.konto_ispublic=:konto_ispublic: AND ks.sepa_ispublic=:sepa_ispublic: AND r.rechnung_datum>=ks.sepa_erste AND (r.rechnung_datum<=ks.sepa_letzte OR ks.sepa_letzte=:sepa_letzte:) ORDER BY r.rechnung_id ASC');
		$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QselectData->bindTable(':table_weberp_kunde:', 'weberp_kunde');
		$QselectData->bindTable(':table_weberp_kunde_konto:', 'weberp_kunde_konto');
		$QselectData->bindTable(':table_weberp_kunde_sepa:', 'weberp_kunde_sepa');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':rechnung_bezahlt:', 0);
		$QselectData->bindInt(':rechnung_storniert:', 0);
		$QselectData->bindInt(':konto_ispublic:', 1);
		$QselectData->bindInt(':sepa_ispublic:', 1);
		$QselectData->bindString(':sepa_letzte:', '00000000');
		foreach ($QselectData->query() as $sepa) {
			if ($sepa['rechnung_kunde_firma']=='') {
				$sepa['rechnung_kunde_firma']=trim($sepa['rechnung_kunde_anrede'].' '.$sepa['rechnung_kunde_vorname'].' '.$sepa['rechnung_kunde_nachname']);
			}

			# OOFF (einmalige Lastschrift)
			# FRST (erste Lastschrift)
			# RCUR (wiederholte Lastschrift)
			# FNAL (letzte Lastschrift)
			if ((substr($sepa['rechnung_datum'], 0, 6)==substr($sepa['kunde_sepa_erste'], 0, 6))&&(substr($sepa['rechnung_datum'], 0, 6)==substr($sepa['kunde_sepa_letzte'], 0, 6))) {
				$typ='OOFF';
			} elseif (substr($sepa['rechnung_datum'], 0, 6)==substr($sepa['kunde_sepa_erste'], 0, 6)) {
				$typ='FRST';
			} elseif (substr($sepa['rechnung_datum'], 0, 6)==substr($sepa['kunde_sepa_letzte'], 0, 6)) {
				$typ='FNAL';
			} else {
				$typ='RCUR';
			}

			$_sepa=[];
			$_sepa['aDatum']=date('Y-m-d', $ts);
			$_sepa['aBetrag']=$sepa['rechnung_gesamt_brutto'];
			$_sepa['aName']=$sepa['rechnung_kunde_firma'];
			$_sepa['aIban']=str_replace(' ', '', $sepa['konto_iban']);
			$_sepa['aBic']=$sepa['konto_bic'];
			$_sepa['aCtgyPurp']=null;
			$_sepa['aPurp']=null;
			$_sepa['aRef']=$sepa['rechnung_nr'];
			$_sepa['aVerwend']='Rechnung '.$sepa['rechnung_nr'];
			$_sepa['aSeqTp']=$typ;
			$_sepa['aMandatRef']=$sepa['sepa_mandat'];
			$_sepa['aMandatDate']=substr($sepa['sepa_erste'], 0, 4).'-'.substr($sepa['sepa_erste'], 4, 2).'-'.substr($sepa['sepa_erste'], 6, 2);
			$_sepa['aOldMandatRef']=null;
			$_sepa['aOldName']=null;
			$_sepa['aOldCreditorId']=null;
			$_sepa['aOldIban']=null;
			$_sepa['aOldBic']=null;

			$data[]=$_sepa;
		}

		return $data;
	}

	/**
	 * @return array
	 */
	public function getSepaAusgang():array {
		$ts=self::getSepaTimeStamp();

		$lohn_jahr=date('Y', mktime(date('H'), date('i'), date('s'), date('n')-1, date('j'), date('Y')));
		$lohn_monat=date('m', mktime(date('H'), date('i'), date('s'), date('n')-1, date('j'), date('Y')));

		$data=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_lohn: AS l LEFT JOIN :table_weberp_mitarbeiter: AS m ON (m.mitarbeiter_id=l.mitarbeiter_id) WHERE m.mandant_id=:mandant_id: AND l.lohn_jahr=:lohn_jahr: AND l.:lohn_x_brutto:>:lohn_brutto:');
		$QselectData->bindTable(':table_weberp_lohn:', 'weberp_lohn');
		$QselectData->bindTable(':table_weberp_mitarbeiter:', 'weberp_mitarbeiter');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':lohn_jahr:', $lohn_jahr);
		$QselectData->bindRaw(':lohn_x_brutto:', 'lohn_'.sprintf('%02d', $lohn_monat).'_brutto');
		$QselectData->bindInt(':lohn_brutto:', 0);
		foreach ($QselectData->query() as $sepa) {
			$_sepa=[];
			$_sepa['aDatum']=date('Y-m-d', $ts);
			$_sepa['aBetrag']=$sepa['lohn_'.sprintf('%02d', $lohn_monat).'_netto'];
			$_sepa['aName']=trim($sepa['mitarbeiter_nachname'].' '.$sepa['mitarbeiter_vorname']);
			$_sepa['aIban']=$sepa['mitarbeiter_iban'];
			$_sepa['aBic']=$sepa['mitarbeiter_bic'];
			$_sepa['aCtgyPurp']='SALA';
			$_sepa['aPurp']='SALA';
			$_sepa['aRef']=$sepa['mitarbeiter_nr'].'/'.$lohn_jahr.sprintf('%02d', $lohn_monat);
			$_sepa['aVerwend']='Gehalt '.$lohn_jahr.'/'.sprintf('%02d', $lohn_monat);
			$_sepa['aSeqTp']=null;
			$_sepa['aMandatRef']=null;
			$_sepa['aMandatDate']=null;
			$_sepa['aOldMandatRef']=null;
			$_sepa['aOldName']=null;
			$_sepa['aOldCreditorId']=null;
			$_sepa['aOldIban']=null;
			$_sepa['aOldBic']=null;

			$data[]=$_sepa;
		}

		return $data;
	}

	/**
	 * @param array $sepa_data
	 * @param string $aType
	 * @param string $aMsgId
	 * @param string $aPmtInfId
	 * @param string $aInitgPty
	 * @param string $aAuftraggeber
	 * @param string $aIban
	 * @param string $aBic
	 * @param string|null $aCreditorId
	 * @return string
	 */
	public static function getSepaXML(array $sepa_data, string $aType, string $aMsgId, string $aPmtInfId, string $aInitgPty, string $aAuftraggeber, string $aIban, string $aBic, ?string $aCreditorId=null):string {
		$osW_Sepa=new osWFrame\KtoSepaSimple();
		foreach ($sepa_data as $sepa) {
			$osW_Sepa->Add($sepa['aDatum'], $sepa['aBetrag'], $sepa['aName'], $sepa['aIban'], $sepa['aBic'], $sepa['aCtgyPurp'], $sepa['aPurp'], $sepa['aRef'], $sepa['aVerwend'], $sepa['aSeqTp'], $sepa['aMandatRef'], $sepa['aMandatDate'], $sepa['aOldMandatRef'], $sepa['aOldName'], $sepa['aOldCreditorId'], $sepa['aOldIban'], $sepa['aOldBic']);
		}

		return $osW_Sepa->GetXML($aType, $aMsgId, $aPmtInfId, $aInitgPty, $aAuftraggeber, $aIban, $aBic, $aCreditorId);
	}

}

?>