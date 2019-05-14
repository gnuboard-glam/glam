<?php

namespace Glam;

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

	function bodyClass()
	{
		$classList = [];

		$classList[] = $this->content;

		return \trim($this->_bodyClass . ' page_' . \implode(' page_', $classList));
	}

	function setBodyClass(string $className)
	{
		$this->_bodyClass .= ' ' . $className;
	}
}