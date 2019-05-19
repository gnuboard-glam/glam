<?php

namespace Glam;

use Dot\Dev\Console;
use Dot\Dot;
use Dot\Html\Head;
use Dot\Strings;

class GlamBoard extends GlamBase
{
	use GlamBoardTrait;

	// don't know
	protected $_scriptLinked = [
		'password'
	];

	// using same external script
	protected $_scriptGroup = [
		'login|register.*' => 'member'
	];

	protected $_alters = [
		'login'    => '회원 인증',
		'register' => '회원 가입'
	];

	/**
	 * filename of uri
	 * @var string
	 */
	protected $content;

	/**
	 * is page of index
	 */
	public $isIndex;

	/**
	 * is page of board
	 * @var boolean
	 */
	public $isBoard;
	/**
	 * is page of shop
	 * @var boolean
	 */
	public $isShop;


	/**
	 * is page of content
	 * @var boolean
	 */
	public $isContent;


	protected $_bodyClass = '';

	/**
	 * locales array
	 * @var array
	 */
	protected $_locales;

	/**
	 * selected locale
	 * @var string
	 */
	public $locale;

	/**
	 * @var string the phtml file path
	 */
	public $localeHead;

	/**
	 * @var string the phtml file path
	 */
	public $localeTail;

	/**
	 * @var string the phtml file path
	 */
	public $localeFoot;

	/**
	 * @var string the phtml file path
	 */
	public $localeIndex;

	function glam_ready()
	{
		parent::glam_ready();
		$this->glam_board();

		$script = \pathinfo($_SERVER['SCRIPT_NAME'], \PATHINFO_FILENAME);

		$slugs = &$this->_slugs;
		$root = &$slugs[0];

		$content = $script;

		$this->content = $content;
		$this->isIndex = $content === 'index';
	}

	function locale()
	{
		if (!$this->locale) {
			global $theme_config;

			if (!isset($theme_config)) {
				die('Cannot found theme configure');
			}


			if (!isset($theme_config['locales']) || !isset($theme_config['locale'])) {
				die('Cannot found locale values in theme configure');
			}

			$locales = $theme_config['locales'];
			$defaultLocale = $theme_config['locale'];

			$fixedLocales = [];
			foreach ($locales as $locale => $localeLabel) {
				if (is_numeric($locale)) {
					$locale = $localeLabel;
				}
				$fixedLocales[$locale] = $localeLabel;
			}

			unset($theme_config['locales'], $theme_config['locale']);

			$this->_locales = $fixedLocales;

			$slug = $this->_slugs[0] ?? null;

			$locale = $defaultLocale;

			if ($slug) {
				if (isset($fixedLocales[$slug])) {
					$locale = $slug;
					set_session('locale', $locale);
				}
			} elseif (isset($_SESSION['locale'])) {
				$locale = &$_SESSION['locale'];
			}

			$this->locale = $locale;

			$this->localeHead = GNU_CONTENTS . $locale . '/head.phtml';
			$this->localeFoot = GNU_CONTENTS . $locale . '/foot.phtml';
			$this->localeTail =& $this->localeFoot;
			$this->localeIndex = GNU_CONTENTS . $locale . '.phtml';
		}

		return $this->locale;
	}

	function bodyClass()
	{
		$classList = $this->getBodyClass();

		return \trim($this->_bodyClass . ' page_' . \implode(' page_', $classList));
	}

	function getBodyClass()
	{
		return [$this->content];
	}

	function setBodyClass(string $className)
	{
		$this->_bodyClass .= ' ' . $className;
	}
}