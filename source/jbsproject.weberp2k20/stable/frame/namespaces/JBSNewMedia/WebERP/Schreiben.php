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

class Schreiben {

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
	private array $schreiben_details=[];

	/**
	 * @var int
	 */
	private int $schreiben_id=0;

	/**
	 * @var bool
	 */
	private bool $loaded=false;

	/**
	 * Schreiben constructor.
	 *
	 * @param int $mandant_id
	 * @param int $schreiben_id
	 */
	public function __construct(int $mandant_id, int $schreiben_id=0) {
		$this->init();
		$this->setMandantId($mandant_id);
		if ($schreiben_id>0) {
			$this->setSchreibenId($schreiben_id);
			$this->load();
		}
	}

	/**
	 * @return bool
	 */
	public function init():bool {
		$this->schreiben_details=[];
		$this->setSchreibenId(0);
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
	 * @param int $schreiben_id
	 * @return bool
	 */
	public function setSchreibenId(int $schreiben_id=0):bool {
		$this->schreiben_id=$schreiben_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getSchreibenId():int {
		return $this->schreiben_id;
	}

	/**
	 * @return bool
	 */
	public function load():bool {
		if ($this->loadDetails()!==true) {
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
		$QselectData->prepare('SELECT * FROM :table_weberp_schreiben: WHERE mandant_id=:mandant_id: AND schreiben_id=:schreiben_id:');
		$QselectData->bindTable(':table_weberp_schreiben:', 'weberp_schreiben');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':schreiben_id:', $this->getSchreibenId());
		$QselectData->execute();
		if ($QselectData->rowCount()==1) {
			$this->schreiben_details=$QselectData->fetch();

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getDetails():array {
		return $this->schreiben_details;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getDetailValue(string $key):string {
		if (isset($this->schreiben_details[$key])) {
			return $this->schreiben_details[$key];
		}

		return '';
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param int $vis_user_id
	 * @return bool
	 */
	public function updateStringValue(string $key, string $value, int $vis_user_id):bool {
		$QupdateData=self::getConnection();
		$QupdateData->prepare('UPDATE :table_weberp_schreiben: SET :key:=:value:, schreiben_update_time=:schreiben_update_time:, schreiben_update_user_id=:schreiben_update_user_id: WHERE mandant_id=:mandant_id: AND schreiben_id=:schreiben_id:');
		$QupdateData->bindTable(':table_weberp_schreiben:', 'weberp_schreiben');
		$QupdateData->bindRaw(':key:', $key);
		$QupdateData->bindValue(':value:', $value);
		$QupdateData->bindInt(':schreiben_update_time:', time());
		$QupdateData->bindInt(':schreiben_update_user_id:', $vis_user_id);
		$QupdateData->bindInt(':mandant_id:', $this->getMandantId());
		$QupdateData->bindInt(':schreiben_id:', $this->getSchreibenId());
		$QupdateData->execute();

		return true;
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @param int $vis_user_id
	 * @return bool
	 */
	public function updateIntValue(string $key, int $value, int $vis_user_id):bool {
		$QupdateData=self::getConnection();
		$QupdateData->prepare('UPDATE :table_weberp_schreiben: SET :key:=:value:, schreiben_update_time=:schreiben_update_time:, schreiben_update_user_id=:schreiben_update_user_id: WHERE mandant_id=:mandant_id: AND schreiben_id=:schreiben_id:');
		$QupdateData->bindTable(':table_weberp_schreiben:', 'weberp_schreiben');
		$QupdateData->bindRaw(':key:', $key);
		$QupdateData->bindInt(':value:', $value);
		$QupdateData->bindInt(':schreiben_update_time:', time());
		$QupdateData->bindInt(':schreiben_update_user_id:', $vis_user_id);
		$QupdateData->bindInt(':mandant_id:', $this->getMandantId());
		$QupdateData->bindInt(':schreiben_id:', $this->getSchreibenId());
		$QupdateData->execute();

		return true;
	}

	/**
	 * @return string
	 */
	public function getPath():string {
		$dir=osWFrame\Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'weberp'.DIRECTORY_SEPARATOR.'schreiben'.DIRECTORY_SEPARATOR;
		if (osWFrame\Filesystem::isDir($dir)!==true) {
			osWFrame\Filesystem::makeDir($dir);
		}

		return $dir;
	}

}

?>