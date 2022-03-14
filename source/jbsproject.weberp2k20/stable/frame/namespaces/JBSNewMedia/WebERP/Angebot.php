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

class Angebot {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;
	use VIS2\BaseUserTrait;
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
	private array $angebot_details=[];

	/**
	 * @var array
	 */
	private array $kunde_details=[];

	/**
	 * @var array
	 */
	private array $angebot_positionen=[];

	/**
	 * @var int
	 */
	private int $angebot_id=0;

	/**
	 * @var int
	 */
	private int $kunde_id=0;

	/**
	 * @var bool
	 */
	private bool $loaded=false;

	/**
	 * Angebot constructor.
	 *
	 * @param int $mandant_id
	 * @param int $angebot_id
	 */
	public function __construct(int $mandant_id, int $angebot_id=0) {
		$this->init();
		$this->setMandantId($mandant_id);
		if ($angebot_id>0) {
			$this->setAngebotId($angebot_id);
			$this->load();
		}
	}


	/*
	public function dump() {
		print_a($this->angebot_details);
		print_a($this->kunde_details);
		print_a($this->angebot_positionen);
	}
	*/

	/**
	 * @return bool
	 */
	public function init():bool {
		$this->angebot_details=[];
		$this->kunde_details=[];
		$this->angebot_positionen=[];
		$this->setAngebotId(0);
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
	 * @param int $angebot_id
	 * @return bool
	 */
	public function setAngebotId(int $angebot_id=0):bool {
		$this->angebot_id=$angebot_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getAngebotId():int {
		return $this->angebot_id;
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

		if ($this->loadKunde()!==true) {
			$this->init();

			return false;
		}

		if ($this->loadPositionen()!==true) {
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
		$QselectData->prepare('SELECT * FROM :table_weberp_angebot: WHERE mandant_id=:mandant_id: AND angebot_id=:angebot_id:');
		$QselectData->bindTable(':table_weberp_angebot:', 'weberp_angebot');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':angebot_id:', $this->getAngebotId());
		$QselectData->execute();
		if ($QselectData->rowCount()==1) {
			$this->angebot_details=$QselectData->fetch();
			$this->setKundeId($this->angebot_details['kunde_id']);

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getDetails():array {
		return $this->angebot_details;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getDetailValue(string $key):string {
		if (isset($this->angebot_details[$key])) {
			return $this->angebot_details[$key];
		}

		return '';
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param int $vis_user_id
	 * @return bool
	 */
	public function updateStringValue(string $key, string $value, int $vis_user_id=0):bool {
		if ($vis_user_id==0) {
			$vis_user_id=$this->getUserId();
		}
		$QupdateData=self::getConnection();
		$QupdateData->prepare('UPDATE :table_weberp_angebot: SET :key:=:value:, angebot_update_time=:angebot_update_time:, angebot_update_user_id=:angebot_update_user_id: WHERE mandant_id=:mandant_id: AND angebot_id=:angebot_id:');
		$QupdateData->bindTable(':table_weberp_angebot:', 'weberp_angebot');
		$QupdateData->bindRaw(':key:', $key);
		$QupdateData->bindValue(':value:', $value);
		$QupdateData->bindInt(':angebot_update_time:', time());
		$QupdateData->bindInt(':angebot_update_user_id:', $vis_user_id);
		$QupdateData->bindInt(':mandant_id:', $this->getMandantId());
		$QupdateData->bindInt(':angebot_id:', $this->getAngebotId());
		$QupdateData->execute();

		return true;
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @param int $vis_user_id
	 * @return bool
	 */
	public function updateIntValue(string $key, int $value, int $vis_user_id=0):bool {
		if ($vis_user_id==0) {
			$vis_user_id=$this->getUserId();
		}
		$QupdateData=self::getConnection();
		$QupdateData->prepare('UPDATE :table_weberp_angebot: SET :key:=:value:, angebot_update_time=:angebot_update_time:, angebot_update_user_id=:angebot_update_user_id: WHERE mandant_id=:mandant_id: AND angebot_id=:angebot_id:');
		$QupdateData->bindTable(':table_weberp_angebot:', 'weberp_angebot');
		$QupdateData->bindRaw(':key:', $key);
		$QupdateData->bindInt(':value:', $value);
		$QupdateData->bindInt(':angebot_update_time:', time());
		$QupdateData->bindInt(':angebot_update_user_id:', $vis_user_id);
		$QupdateData->bindInt(':mandant_id:', $this->getMandantId());
		$QupdateData->bindInt(':angebot_id:', $this->getAngebotId());
		$QupdateData->execute();

		return true;
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
	 * @return bool
	 */
	public function loadPositionen():bool {
		$this->clearPositions();
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_angebot_position: WHERE mandant_id=:mandant_id: AND angebot_id=:angebot_id: ORDER BY position_pos ASC');
		$QselectData->bindTable(':table_weberp_angebot_position:', 'weberp_angebot_position');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':angebot_id:', $this->getAngebotId());
		foreach ($QselectData->query() as $position) {
			$this->angebot_positionen[$position['position_pos']]=$position;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function clearPositions():bool {
		$this->angebot_positionen=[];

		return true;
	}

	/**
	 * @param array $positionen
	 * @return bool
	 */
	public function setPositionen(array $positionen):bool {
		foreach ($positionen as $key=>$values) {
			$this->setPosition($key, $values);
		}

		return true;
	}

	/**
	 * @param array $positionen
	 * @return bool
	 */
	public function setPosition(int $key, array $position):bool {
		if (!isset($this->angebot_positionen[$key])) {
			$this->angebot_positionen[$key]=$this->initPosition();
		}
		foreach ($position as $pkey=>$pvalue) {
			$this->angebot_positionen[$key][$pkey]=$pvalue;
		}

		return true;
	}

	/**
	 * @param bool $fillempty
	 * @return array
	 */
	public function getPositionen(bool $fillempty=false):array {
		if ($fillempty===false) {
			return $this->angebot_positionen;
		} else {
			$position=$this->angebot_positionen;
			for ($i=1; $i<=Verwaltung::getPositionsMax(); $i++) {
				if (!isset($position[$i])) {
					$position[$i]=$this->initPosition();
				}
			}

			return $position;
		}
	}

	/**
	 * @param int $key
	 * @return array
	 */
	public function getPosition(int $key):array {
		if (isset($this->angebot_positionen[$key])) {
			return $this->angebot_positionen[$key];
		}

		return $this->initPosition();
	}

	/**
	 * @return array
	 */
	public function initPosition():array {
		return ['position_id'=>0, 'mandant_id'=>0, 'angebot_id'=>0, 'position_pos'=>0, 'position_artikel_anzahl'=>0.0, 'position_artikel_id'=>0, 'position_artikel_cron'=>0, 'position_artikel_zusatz'=>'', 'position_artikel_nr'=>0, 'position_artikel_kurz'=>'', 'position_artikel_beschreibung'=>'', 'position_artikel_beschreibung_ausblenden'=>'', 'position_artikel_preis'=>0.0, 'position_artikel_typ'=>0, 'position_artikel_mwst'=>0, 'position_create_time'=>0, 'position_create_user_id'=>0, 'position_update_time'=>0, 'position_update_user_id'=>0];
	}

	/**
	 * @return bool
	 */
	public function savePositionen():bool {
		$time=time();
		$user_id=$this->getUserId();

		for ($i=1; $i<=Verwaltung::getPositionsMax(); $i++) {
			$position=$this->getPosition($i);
			if ($position['position_artikel_id']>0) {
				$QinsertData=self::getConnection();
				$QinsertData->prepare('REPLACE INTO :table_weberp_angebot_position: (position_id, angebot_id, mandant_id, position_pos, position_artikel_anzahl, position_artikel_id, position_artikel_zusatz, position_artikel_nr, position_artikel_kurz, position_artikel_beschreibung, position_artikel_beschreibung_ausblenden, position_artikel_preis, position_artikel_typ, position_create_time, position_create_user_id, position_update_time, position_update_user_id, position_artikel_mwst) VALUES (:position_id:, :angebot_id:, :mandant_id:, :position_pos:, :position_artikel_anzahl:, :position_artikel_id:, :position_artikel_zusatz:, :position_artikel_nr:, :position_artikel_kurz:, :position_artikel_beschreibung:, :position_artikel_beschreibung_ausblenden:, :position_artikel_preis:, :position_artikel_typ:, :position_create_time:, :position_create_user_id:, :position_update_time:, :position_update_user_id:, :position_artikel_mwst:)');
				$QinsertData->bindTable(':table_weberp_angebot_position:', 'weberp_angebot_position');
				$QinsertData->bindInt(':position_id:', $position['position_id']);
				$QinsertData->bindInt(':angebot_id:', $this->getAngebotId());
				$QinsertData->bindInt(':mandant_id:', $this->getMandantId());
				$QinsertData->bindInt(':position_pos:', $i);
				$QinsertData->bindFloat(':position_artikel_anzahl:', $position['position_artikel_anzahl']);
				$QinsertData->bindInt(':position_artikel_id:', $position['position_artikel_id']);
				$QinsertData->bindString(':position_artikel_zusatz:', $position['position_artikel_zusatz']);
				$QinsertData->bindInt(':position_artikel_nr:', $position['position_artikel_nr']);
				$QinsertData->bindString(':position_artikel_kurz:', $position['position_artikel_kurz']);
				$QinsertData->bindString(':position_artikel_beschreibung:', $position['position_artikel_beschreibung']);
				$QinsertData->bindString(':position_artikel_beschreibung_ausblenden:', $position['position_artikel_beschreibung_ausblenden']);
				$QinsertData->bindString(':position_artikel_preis:', $position['position_artikel_preis']);
				$QinsertData->bindInt(':position_artikel_typ:', $position['position_artikel_typ']);
				$QinsertData->bindInt(':position_artikel_mwst:', $position['position_artikel_mwst']);
				$QinsertData->bindInt(':position_create_time:', $time);
				$QinsertData->bindInt(':position_create_user_id:', $user_id);
				$QinsertData->bindInt(':position_update_time:', $time);
				$QinsertData->bindInt(':position_update_user_id:', $user_id);
				$QinsertData->execute();
			} else {
				$QdelData=self::getConnection();
				$QdelData->prepare('DELETE FROM :table_weberp_angebot_position: WHERE angebot_id=:angebot_id: AND mandant_id=:mandant_id: AND position_pos=:position_pos:');
				$QdelData->bindTable(':table_weberp_angebot_position:', 'weberp_angebot_position');
				$QdelData->bindInt(':angebot_id:', $this->getAngebotId());
				$QdelData->bindInt(':mandant_id:', $this->getMandantId());
				$QdelData->bindInt(':position_pos:', $i);
				$QdelData->execute();
			}
		}

		return true;
	}

	/**
	 * @param array $elements
	 * @return bool
	 */
	public function setPositionenByNumericArray(array $elements):bool {
		for ($i=1; $i<=Verwaltung::getPositionsMax(); $i++) {
			if ((!isset($elements['position_artikel_'.$i.'_id']))||(intval($elements['position_artikel_'.$i.'_id'])==0)) {
				$this->setPosition($i, $this->initPosition());
			} else {
				$position=[];
				foreach (['id', 'cron', 'nr', 'typ', 'mwst'] as $key) {
					if (!isset($elements['position_artikel_'.$i.'_'.$key])) {
						$position['position_artikel_'.$key]=0;
					} else {
						$position['position_artikel_'.$key]=intval($elements['position_artikel_'.$i.'_'.$key]);
					}
				}
				foreach (['zusatz', 'kurz', 'beschreibung', 'beschreibung_ausblenden'] as $key) {
					if (!isset($elements['position_artikel_'.$i.'_'.$key])) {
						$position['position_artikel_'.$key]=0;
					} else {
						$position['position_artikel_'.$key]=strval($elements['position_artikel_'.$i.'_'.$key]);
					}
				}
				foreach (['anzahl', 'preis'] as $key) {
					if (!isset($elements['position_artikel_'.$i.'_'.$key])) {
						$position['position_artikel_'.$key]=0;
					} else {
						$position['position_artikel_'.$key]=floatval($elements['position_artikel_'.$i.'_'.$key]);
					}
				}
				$this->setPosition($i, $position);
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function setSumme():bool {
		$gesamt_netto=0;
		$gesamt_mwst=0;
		foreach ($this->angebot_positionen as $element) {
			if ($element['position_artikel_id']>0) {
				$_gesamt_netto=0;
				$_gesamt_mwst=0;

				$_gesamt_netto=round($element['position_artikel_anzahl']*$element['position_artikel_preis'], 2);
				$_gesamt_mwst=round($_gesamt_netto*((100+$element['position_artikel_mwst'])/100), 2)-$_gesamt_netto;

				$gesamt_netto=$gesamt_netto+$_gesamt_netto;
				$gesamt_mwst=$gesamt_mwst+$_gesamt_mwst;
			}

		}
		$QupdateData=self::getConnection();
		$QupdateData->prepare('UPDATE :table_weberp_angebot: SET angebot_gesamt_brutto=:angebot_gesamt_brutto:, angebot_gesamt_netto=:angebot_gesamt_netto:, angebot_gesamt_mwst=:angebot_gesamt_mwst: WHERE angebot_id=:angebot_id:');
		$QupdateData->bindTable(':table_weberp_angebot:', 'weberp_angebot');
		$QupdateData->bindFloat(':angebot_gesamt_brutto:', number_format(($gesamt_netto+$gesamt_mwst), 2, '.', ''));
		$QupdateData->bindFloat(':angebot_gesamt_netto:', number_format($gesamt_netto, 2, '.', ''));
		$QupdateData->bindFloat(':angebot_gesamt_mwst:', number_format($gesamt_mwst, 2, '.', ''));
		$QupdateData->bindInt(':angebot_id:', $this->getAngebotId());
		$QupdateData->execute();

		return true;
	}

	/**
	 * @return string
	 */
	public function getPath():string {
		$dir=osWFrame\Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'weberp'.DIRECTORY_SEPARATOR.'angebot'.DIRECTORY_SEPARATOR;
		if (osWFrame\Filesystem::isDir($dir)!==true) {
			osWFrame\Filesystem::makeDir($dir);
		}

		return $dir;
	}

}

?>