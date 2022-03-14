<?php

/**
 * This file is part of the VIS2:WebDMS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebDMS
 * @link https://jbs-newmedia.com
 * @license MIT License
 */

namespace JBSNewMedia\WebDMS;

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
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var array
	 */
	private array $status=[];

	/**
	 * @var array
	 */
	private array $typ=[];

	/**
	 * @var array
	 */
	private array $ordner=[];

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
	 * @return bool
	 */
	public function load():bool {
		if ($this->getMandantId()==0) {
			return false;
		}

		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_webdms_config: WHERE mandant_id=:mandant_id:');
		$QselectData->bindTable(':table_webdms_config:', 'webdms_config');
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
	 * @param int $parent_id
	 * @param int $level
	 * @param int $max_level
	 * @param array $data
	 * @return array
	 */
	public function createOrdnerRecursive(int $parent_id, int $level=0, int $max_level=0, array $data=[]):array {
		if (!isset($data['title'][0])) {
			$data['title'][0]='Bitte wählen';
		}

		$Qselect=self::getConnection();
		$Qselect->prepare('SELECT * FROM :table_webdms_ordner: WHERE mandant_id=:mandant_id: AND ordner_ispublic=:ordner_ispublic: AND ordner_parent_id=:ordner_parent_id: ORDER BY ordner_intern_sortorder ASC');
		$Qselect->bindTable(':table_webdms_ordner:', 'webdms_ordner');
		$Qselect->bindInt(':mandant_id:', $this->getMandantId());
		$Qselect->bindInt(':ordner_ispublic:', 1);
		$Qselect->bindInt(':ordner_parent_id:', $parent_id);
		foreach ($Qselect->query() as $dir) {
			$data['level'][$dir['ordner_id']]=$level;
			if ($level==0) {
				$data['title'][$dir['ordner_id']]=$dir['ordner_titel'];
			} else {
				$data['title'][$dir['ordner_id']]=$data['title'][$dir['ordner_parent_id']].' > '.$dir['ordner_titel'];
			}

			if ($level+1<$max_level) {
				$data=$this->createOrdnerRecursive($dir['ordner_id'], $level+1, $max_level, $data);
			}
		}

		return $data;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $fullarray
	 * @return array
	 */
	public function getStatus(string $key='status_id', string $value='status_titel', bool $fullarray=false):array {
		$this->status=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_webdms_status: WHERE status_ispublic=:status_ispublic: AND mandant_id=:mandant_id: ORDER BY :value: ASC');
		$QselectData->bindTable(':table_webdms_status:', 'webdms_status');
		$QselectData->bindInt(':status_ispublic:', 1);
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':value:', $value);
		foreach ($QselectData->query() as $status) {
			if ($fullarray===true) {
				$this->status[$status[$key]]=$status;
			} else {
				$this->status[$status[$key]]=$status[$value];
			}
		}

		return $this->status;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $fullarray
	 * @param int $mandant_id
	 * @return array
	 */
	public function getTyp(string $key='typ_id', string $value='typ_titel', bool $fullarray=false):array {
		$this->typ=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_webdms_typ: WHERE typ_ispublic=:typ_ispublic: AND mandant_id=:mandant_id: ORDER BY :value: ASC');
		$QselectData->bindTable(':table_webdms_typ:', 'webdms_typ');
		$QselectData->bindInt(':typ_ispublic:', 1);
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':value:', $value);
		foreach ($QselectData->query() as $typ) {
			if ($fullarray===true) {
				$this->typ[$typ[$key]]=$typ;
			} else {
				$this->typ[$typ[$key]]=$typ[$value];
			}
		}

		return $this->typ;
	}

	/**
	 * @param int $limit
	 * @param int $time
	 * @return array
	 */
	public function getDokumente2OCR(int $limit=10, int $time=0) {
		$data=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_webdms_dokument: WHERE dokument_index_time<=:dokument_index_time: AND mandant_id=:mandant_id: ORDER BY dokument_id ASC LIMIT 0, :limit:');
		$QselectData->bindTable(':table_webdms_dokument:', 'webdms_dokument');
		if ($time==0) {
			$QselectData->bindInt(':dokument_index_time:', 0);
		} else {
			$QselectData->bindInt(':dokument_index_time:', (time()-$time));
		}
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':limit:', $limit);
		$QselectData->execute();
		foreach ($QselectData->query() as $dokument) {
			$data[]=$dokument;
		}

		return $data;
	}

	/**
	 * @param int $dokument_id
	 * @param string $key
	 * @param $value
	 * @param string $type
	 * @return bool
	 */
	public function updateDokument(int $dokument_id, string $key, $value, string $type):bool {
		$QupdateData=self::getConnection();
		$QupdateData->prepare('UPDATE :table_webdms_dokument: SET :key:=:value: WHERE dokument_id=:dokument_id: AND mandant_id=:mandant_id:');
		$QupdateData->bindTable(':table_webdms_dokument:', 'webdms_dokument');
		$QupdateData->bindInt(':dokument_index_time:', 0);
		$QupdateData->bindRaw(':key:', $key);
		switch ($type) {
			case 'integer':
				$QupdateData->bindInt(':value:', $value);
				break;
			default:
				$QupdateData->bindString(':value:', $value);
				break;
		}
		$QupdateData->bindInt(':dokument_id:', $dokument_id);
		$QupdateData->bindInt(':mandant_id:', $this->getMandantId());
		$QupdateData->execute();

		return true;
	}

	/**
	 * @param int $ordner_id
	 * @return array
	 */
	public function getExplorer(int $ordner_id=0):array {
		$data=[];
		if ($ordner_id>0) {
			$QselectData=self::getConnection();
			$QselectData->prepare('SELECT * FROM :table_webdms_ordner: WHERE ordner_ispublic=:ordner_ispublic: AND mandant_id=:mandant_id: AND ordner_id=:ordner_id:');
			$QselectData->bindTable(':table_webdms_ordner:', 'webdms_ordner');
			$QselectData->bindInt(':ordner_ispublic:', 1);
			$QselectData->bindInt(':mandant_id:', $this->getMandantId());
			$QselectData->bindRaw(':ordner_id:', $ordner_id);
			$QselectData->execute();
			$data['current']=[];
			if ($QselectData->rowCount()==1) {
				$data['current']=$QselectData->fetch();
			}

			$data['breadcrumb']=[];
			if ($ordner_id==$data['current']['ordner_id']) {
				$data['current']['current']=true;
			} else {
				$data['current']['current']=false;
			}
			$data['breadcrumb'][]=$data['current'];
			while ($data['current']['ordner_parent_id']!=0) {
				$ordner_parent_id=$data['current']['ordner_parent_id'];
				$QselectData=self::getConnection();
				$QselectData->prepare('SELECT * FROM :table_webdms_ordner: WHERE ordner_ispublic=:ordner_ispublic: AND mandant_id=:mandant_id: AND ordner_id=:ordner_id:');
				$QselectData->bindTable(':table_webdms_ordner:', 'webdms_ordner');
				$QselectData->bindInt(':ordner_ispublic:', 1);
				$QselectData->bindInt(':mandant_id:', $this->getMandantId());
				$QselectData->bindRaw(':ordner_id:', $ordner_parent_id);
				if ($QselectData->exec()==1) {
					$result=$QselectData->fetch();
					if ($ordner_id==$result['ordner_id']) {
						$result['current']=true;
					} else {
						$result['current']=false;
					}
					$data['breadcrumb'][]=$result;
				}
			}
			$data['breadcrumb']=array_reverse($data['breadcrumb']);
		}

		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_webdms_ordner: WHERE ordner_ispublic=:ordner_ispublic: AND mandant_id=:mandant_id: AND ordner_parent_id=:ordner_parent_id: ORDER BY ordner_intern_sortorder ASC, ordner_titel ASC');
		$QselectData->bindTable(':table_webdms_ordner:', 'webdms_ordner');
		$QselectData->bindInt(':ordner_ispublic:', 1);
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':ordner_parent_id:', $ordner_id);
		$data['dirs']=[];
		foreach ($QselectData->query() as $ordner) {
			$data['dirs'][]=$ordner;
		}

		if ($ordner_id==0) {
			$data['files']=[];
		} else {
			$QselectData=self::getConnection();
			$QselectData->prepare('SELECT * FROM :table_webdms_dokument: WHERE dokument_ispublic=:dokument_ispublic: AND mandant_id=:mandant_id: AND (ordner_id_1=:ordner_id: OR ordner_id_2=:ordner_id: OR ordner_id_3=:ordner_id: OR ordner_id_4=:ordner_id: OR ordner_id_5=:ordner_id: OR ordner_id_6=:ordner_id: OR ordner_id_7=:ordner_id: OR ordner_id_8=:ordner_id: OR ordner_id_9=:ordner_id: OR ordner_id_10=:ordner_id:) ORDER BY dokument_datum DESC, dokument_titel ASC');
			$QselectData->bindTable(':table_webdms_dokument:', 'webdms_dokument');
			$QselectData->bindInt(':dokument_ispublic:', 1);
			$QselectData->bindInt(':mandant_id:', $this->getMandantId());
			$QselectData->bindRaw(':ordner_id:', $ordner_id);
			foreach ($QselectData->query() as $dokument) {
				$data['files'][]=$dokument;
			}
		}

		return $data;
	}

	/**
	 * @param int $datei_id
	 * @return array
	 */
	public function getDatei(int $datei_id=0):array {
		$data=[];

		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_webdms_dokument: WHERE dokument_id=:dokument_id: AND dokument_ispublic=:dokument_ispublic: AND mandant_id=:mandant_id:');
		$QselectData->bindTable(':table_webdms_dokument:', 'webdms_dokument');
		$QselectData->bindInt(':dokument_ispublic:', 1);
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':dokument_id:', $datei_id);
		if ($QselectData->exec()==1) {
			$data=$QselectData->fetch();

			$QselectData2=self::getConnection();
			$QselectData2->prepare('SELECT * FROM :table_webdms_ordner: WHERE ordner_ispublic=:ordner_ispublic: AND mandant_id=:mandant_id: AND ordner_id=:ordner_id:');
			$QselectData2->bindTable(':table_webdms_ordner:', 'webdms_ordner');
			$QselectData2->bindInt(':ordner_ispublic:', 1);
			$QselectData2->bindInt(':mandant_id:', $this->getMandantId());
			$QselectData2->bindInt(':ordner_id:', $data['ordner_id_1']);
			$QselectData2->execute();

			$data['current']=[];

			if ($QselectData2->exec()==1) {
				$data['current']=$QselectData2->fetch();
			}
		}

		return $data;
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
		$QgetData->prepare('SELECT * FROM :table_webdms_config: WHERE mandant_id=:mandant_id: AND config_name=:config_name:');
		$QgetData->bindTable(':table_webdms_config:', 'webdms_config');
		$QgetData->bindInt(':mandant_id:', $mandant_id);
		$QgetData->bindString(':config_name:', $key);
		$QgetData->execute();
		if ($QgetData->rowCount()>1) {
			$QdeleteData=self::getConnection();
			$QdeleteData->prepare('DELETE FROM :table_webdms_config: WHERE mandant_id=:mandant_id: AND config_name=:config_name:');
			$QdeleteData->bindTable(':table_webdms_config:', 'webdms_config');
			$QdeleteData->bindInt(':mandant_id:', $mandant_id);
			$QdeleteData->bindString(':config_name:', $key);
			$QdeleteData->execute();
		}
		if ($QgetData->rowCount()==1) {
			$QgetData->fetch();
			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_webdms_config: SET :config_typename:=:config_value:, config_update_time=:config_update_time:, config_update_user_id=:config_update_user_id: WHERE config_id=:config_id:');
			$QupdateData->bindTable(':table_webdms_config:', 'webdms_config');
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
			$QinsertData->prepare('INSERT INTO :table_webdms_config: (mandant_id, config_name, config_description, :config_typename:, config_type, config_ispublic, config_create_time, config_create_user_id, config_update_time, config_update_user_id) VALUES (:mandant_id:, :config_name:, :config_description:, :config_value:, :config_type:, :config_ispublic:, :config_create_time:, :config_create_user_id:, :config_update_time:, :config_update_user_id:)');
			$QinsertData->bindTable(':table_webdms_config:', 'webdms_config');
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
	 * @param int $i
	 * @param int $parent_id
	 * @param int $level
	 * @param int $mandant_id
	 * @return int
	 */
	public static function updateSortOrdnerRecursive(int $i=0, int $parent_id, int $level, int $mandant_id):int {
		$Qselect=self::getConnection();
		$Qselect->prepare('SELECT * FROM :table_webdms_ordner: WHERE mandant_id=:mandant_id: AND ordner_parent_id=:ordner_parent_id: ORDER BY ordner_sortorder ASC, ordner_titel ASC');
		$Qselect->bindTable(':table_webdms_ordner:', 'webdms_ordner');
		$Qselect->bindInt(':mandant_id:', $mandant_id);
		$Qselect->bindInt(':ordner_parent_id:', $parent_id);
		foreach ($Qselect->query() as $ordner) {
			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_webdms_ordner: SET ordner_intern_sortorder=:ordner_intern_sortorder: WHERE mandant_id=:mandant_id: AND ordner_id=:ordner_id:');
			$QupdateData->bindTable(':table_webdms_ordner:', 'webdms_ordner');
			$QupdateData->bindInt(':ordner_intern_sortorder:', $i);
			$QupdateData->bindInt(':mandant_id:', $mandant_id);
			$QupdateData->bindInt(':ordner_id:', $ordner['ordner_id']);
			$QupdateData->execute();
			$i++;

			$i=self::updateSortOrdnerRecursive($i, $ordner['ordner_id'], $level+1, $mandant_id);
		}

		return $i;
	}

}

?>