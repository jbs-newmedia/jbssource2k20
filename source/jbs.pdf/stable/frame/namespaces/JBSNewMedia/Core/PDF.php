<?php

/**
 * This file is part of the Core package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package Core
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace JBSNewMedia\Core;

use osWFrame\Core as osWFrame;

class PDF extends osWFrame\FPDI {

	use osWFrame\BaseStaticTrait;

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
	private array $jbs_files=[];

	/**
	 * @var int
	 */
	private int $jbs_page=0;

	/**
	 * @var int
	 */
	private int $jbs_pages=0;

	/**
	 * @var string
	 */
	private string $jbs_mode='';

	/**
	 * @var bool
	 */
	private bool $jbs_print=false;

	/**
	 * @return int
	 */
	public function incJBSPage():int {
		return $this->jbs_page++;
	}

	/**
	 * @param int $page
	 * @return int
	 */
	public function setJBSPage(int $page):int {
		return $this->jbs_page=$page;
	}

	/**
	 * @return int
	 */
	public function getJBSPage():int {
		return $this->jbs_page;
	}

	/**
	 * @param int $pages
	 * @return int
	 */
	public function setJBSPages(int $pages):int {
		return $this->jbs_pages=$pages;
	}

	/**
	 * @return int
	 */
	public function getJBSPages():int {
		return $this->jbs_pages;
	}

	/**
	 * @param string $mode
	 * @return string
	 */
	public function setJBSMode(string $mode):string {
		return $this->jbs_mode=$mode;
	}

	/**
	 * @return string
	 */
	public function getJBSMode():string {
		return $this->jbs_mode;
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	public function setJBSPrint(bool $value):bool {
		$this->jbs_print=$value;

		return true;
	}

	/**
	 * @return bool
	 */
	public function getJBSPrint():bool {
		return $this->jbs_print;

		return true;
	}

	/**
	 * @param string $file
	 * @param int $page
	 * @return bool
	 */
	public function setJBSPDFFile(string $file, int $page=0):bool {
		$this->jbs_files['pdffile'][$page]=$file;

		return true;
	}

	/**
	 * @param string $file
	 * @param int $page
	 * @return bool
	 */
	public function setJBSPHPHeaderFile(string $file, int $page=0):bool {
		return $this->setJBSPHPFile($file, $page, 'header');
	}

	/**
	 * @param string $file
	 * @param int $page
	 * @return bool
	 */
	public function setJBSPHPFooterFile(string $file, int $page=0):bool {
		return $this->setJBSPHPFile($file, $page, 'footer');

	}

	/**
	 * @param string $file
	 * @param int $page
	 * @param $string
	 */
	private function setJBSPHPFile(string $file, int $page=0, string $position='header'):bool {
		$this->jbs_files['phpfile'][$position][$page]=$file;

		return true;
	}

	/**
	 * @param int $page
	 * @return bool
	 * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
	 * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
	 * @throws \setasign\Fpdi\PdfParser\PdfParserException
	 * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
	 * @throws \setasign\Fpdi\PdfReader\PdfReaderException
	 */
	public function loadJBSPDF(int $page=0):bool {
		if ((!isset($this->jbs_files['pdffile'][$page]))||(!file_exists($this->jbs_files['pdffile'][$page]))) {
			$page=0;
		}
		if ((isset($this->jbs_files['pdffile'][$page]))&&(file_exists($this->jbs_files['pdffile'][$page]))) {
			if (!isset($this->jbs_files['tplIdx'][$page])) {
				$this->setSourceFile($this->jbs_files['pdffile'][$page]);
				$this->jbs_files['tplIdx'][$page]=$this->importPage(1);
			}
			$this->useTemplate($this->jbs_files['tplIdx'][$page]);
		}

		return true;
	}

	/**
	 * @param int $page
	 * @return bool
	 */
	public function loadJBSPHPHeader(int $page=0):bool {
		return $this->loadJBSPHP($page, 'header');
	}

	/**
	 * @param int $page
	 * @return bool
	 */
	public function loadJBSPHPFooter(int $page=0):bool {
		return $this->loadJBSPHP($page, 'footer');
	}

	/**
	 * @param int $page
	 * @param string $position
	 * @return bool
	 */
	private function loadJBSPHP(int $page=0, string $position='header') {
		if ((!isset($this->jbs_files['phpfile'][$position][$page]))||(!file_exists($this->jbs_files['phpfile'][$position][$page]))) {
			$page=0;
		}
		if ((isset($this->jbs_files['phpfile'][$position][$page]))&&(file_exists($this->jbs_files['phpfile'][$position][$page]))) {
			include $this->jbs_files['phpfile'][$position][$page];
		}

		return true;
	}

	/**
	 * @param array $files
	 * @return bool
	 * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
	 * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
	 * @throws \setasign\Fpdi\PdfParser\PdfParserException
	 * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
	 * @throws \setasign\Fpdi\PdfReader\PdfReaderException
	 */
	public function mergeJBSFiles(array $files):bool {
		foreach ($files as $id=>$file) {
			$count=$this->setSourceFile($file);
			for ($i=1; $i<=$count; $i++) {
				$template=$this->importPage($i);
				$size=$this->getTemplateSize($template);
				if ($size['h']>$size['w']) {
					$this->AddPage('P', [$size['w'], $size['h']]);
				} else {
					$this->AddPage('L', [$size['w'], $size['h']]);
				}
				$this->useTemplate($template);
			}
		}

		return true;
	}

	/**
	 * @return bool
	 * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
	 * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
	 * @throws \setasign\Fpdi\PdfParser\PdfParserException
	 * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
	 * @throws \setasign\Fpdi\PdfReader\PdfReaderException
	 */
	public function setHeader():bool {
		$page=$this->PageNo();
		$this->loadJBSPDF($page);
		$this->loadJBSPHPHeader($page);

		return true;
	}

	/**
	 * @return bool
	 */
	public function setFooter():bool {
		$page=$this->PageNo();
		$this->loadJBSPHPFooter($page);

		return true;
	}

	/**
	 * @param string $textval
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @param int $fontsize
	 * @param string $fontstyle
	 * @param string $align
	 * @return bool
	 */
	public function createJBSTextBox(string $textval, int $x=0, int $y=0, int $width=0, int $height=0, int $fontsize=10, string $fontstyle='', string $align='L'):bool {
		$this->SetXY($x, $y);
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->SetFillColor(0, 63, 127);
		$this->MultiCell($width, $height, $textval, 0, $align, false, 1, '', '', true, 0, true);

		return true;
	}

	/**
	 * @param string $_content
	 * @param bool $double
	 * @return string
	 */
	public function drawJBSTable(string $_content, bool $double=true):string {
		$content='';
		$content.='<table border="0" cellpadding="0" cellspacing="0">';
		$content.='<tr>';
		$content.='<td>';
		if ($double===true) {
			$content.='<table border="0" cellpadding="0" cellspacing="2">';
			$content.='<tr>';
			$content.='<td>';
		}
		$content.=$_content;
		if ($double===true) {
			$content.='</td>';
			$content.='</tr>';
			$content.='</table>';
		}
		$content.='</td>';
		$content.='</tr>';
		$content.='</table>';

		return $content;
	}

}

?>