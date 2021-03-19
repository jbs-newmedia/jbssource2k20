<?php

/**
 * This file is part of the JBSNewMedia-Website package
 *
 * Copyright (C) JBS New Media GmbH (https://jbs-newmedia.com) by Juergen Schwind - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited Proprietary and confidential
 */

namespace JBSNewMedia\Site;

use \osWFrame\Core as Frame;

class SynchronizePacker {

	use Frame\BaseStaticTrait;
	use Frame\BaseTemplateBridgeTrait;

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
	 * @param string $rls
	 * @return string|null
	 */
	public static function getVersion(string $rls='stable'):?string {
		$filename=Frame\Settings::getStringVar('settings_abspath').'source'.DIRECTORY_SEPARATOR.'synchronize'.DIRECTORY_SEPARATOR.$rls.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'jbsnm_sync.php';
		if (!file_exists($filename)) {
			return null;
		}
		$content=file_get_contents($filename);
		preg_match('/private \$version_this\=\'([a-zA-Z0-9\. ]+)\'\;/', $content, $matches);
		if (!isset($matches[1])) {
			return null;
		}

		return $matches[1];
	}

	/**
	 * @param string $rls
	 * @return string|null
	 */
	public static function getPackage(string $rls='stable'):?string {
		if (false===($version=self::getVersion($rls))) {
			return null;
		}
		$filename=Frame\Settings::getStringVar('settings_abspath').'source'.DIRECTORY_SEPARATOR.'synchronize'.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.'synchronize-'.strtolower($version).'.zip';

		return 'source'.DIRECTORY_SEPARATOR.'synchronize'.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.'synchronize-'.strtolower($version).'.zip';
	}

}
