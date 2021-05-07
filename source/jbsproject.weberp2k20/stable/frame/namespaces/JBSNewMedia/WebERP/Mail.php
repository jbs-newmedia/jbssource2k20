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

class Mail extends osWFrame\PHPMailer {

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
	 * @var string
	 */
	private string $error_message='';

	/**
	 * Mail constructor.
	 *
	 * @param null $exceptions
	 */
	public function __construct(object $Verwaltung, $exceptions=null) {
		parent::__construct($exceptions);

		$this->setVerwaltung($Verwaltung);

		if (($this->getVerwaltung()->getStringVar('smtp_server')!=null)&&($this->getVerwaltung()->getStringVar('smtp_server')!='')) {
			$this->setHost($this->getVerwaltung()->getStringVar('smtp_server'));
			$this->setPort($this->getVerwaltung()->getIntVar('smtp_port'));
			if (($this->getVerwaltung()->getStringVar('smtp_secure')!=null)&&($this->getVerwaltung()->getStringVar('smtp_secure')!='')) {
				$this->setSMTPSecure($this->getVerwaltung()->getStringVar('smtp_secure'));
			}
			if (($this->getVerwaltung()->getIntVar('smtp_auth')!=null)&&($this->getVerwaltung()->getIntVar('smtp_auth')==1)) {
				$this->setSMTPAuth(true);
			}
			if (($this->getVerwaltung()->getIntVar('smtp_autotls')!=null)&&($this->getVerwaltung()->getIntVar('smtp_autotls')==1)) {
				$this->setSMTPAuth(true);
			}
			if (($this->getVerwaltung()->getStringVar('smtp_username')!=null)&&($this->getVerwaltung()->getStringVar('smtp_username')!='')) {
				$this->setUsername($this->getVerwaltung()->getStringVar('smtp_username'));
			}
			if (($this->getVerwaltung()->getStringVar('smtp_password')!=null)&&($this->getVerwaltung()->getStringVar('smtp_password')!='')) {
				$this->setUsername($this->getVerwaltung()->getStringVar('smtp_password'));
			}
		}
	}

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
	 * @return bool
	 */
	public function sendSepa():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'sepa_mail.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'sepa_mail.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		$this->addAddress($this->getKundeDetail('kunde_email'));
		$this->addBCC($this->getVerwaltung()->getStringVar('email_buchhaltung'));
		$this->setFrom($this->getVerwaltung()->getStringVar('email_buchhaltung'), $this->getVerwaltung()->getStringVar('firma'));
		$this->setSubject('SEPA-Basis-Lastschriftmandat '.$this->getSepaDetail('sepa_mandat'));
		$this->setBody($content);
		if ($this->send()===true) {
			return true;
		} else {
			$this->setErrorMessage($this->ErrorInfo);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function sendRechnung():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'rechnung_mail.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'rechnung_mail.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		$this->addAddress($this->getKundeDetail('kunde_email'));
		$this->addBCC($this->getVerwaltung()->getStringVar('email_buchhaltung'));
		$this->setFrom($this->getVerwaltung()->getStringVar('email_buchhaltung'), $this->getVerwaltung()->getStringVar('firma'));
		$this->setSubject('Rechnung '.$this->getVorgangDetail('rechnung_nr'));
		$this->setBody($content);
		if ($this->send()===true) {
			return true;
		} else {
			$this->setErrorMessage($this->ErrorInfo);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function sendAngebot():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'angebot_mail.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'angebot_mail.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		$this->addAddress($this->getKundeDetail('kunde_email'));
		$this->addBCC($this->getVerwaltung()->getStringVar('email_buchhaltung'));
		$this->setFrom($this->getVerwaltung()->getStringVar('email_buchhaltung'), $this->getVerwaltung()->getStringVar('firma'));
		$this->setSubject('Angebot '.$this->getVorgangDetail('angebot_nr'));
		$this->setBody($content);
		if ($this->send()===true) {
			return true;
		} else {
			$this->setErrorMessage($this->ErrorInfo);
		}

		return false;
	}

	/**
	 * @param string $error_message
	 * @return bool
	 */
	public function setErrorMessage(string $error_message):bool {
		$this->error_message=$error_message;

		return true;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage():string {
		return $this->error_message;
	}

}

?>