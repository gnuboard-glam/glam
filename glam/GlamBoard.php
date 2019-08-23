<?php

namespace Glam;

use Dot\Dev\Console;
use Dot\Dot;
use Dot\Html\Head;
use Dot\Strings;
use function Dot\startWith;

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
        'login' => '회원 인증',
        'register' => '회원 가입'
    ];

    /**
     * filename of uri
     * @var string
     */
    protected $script;

    /**
     * is page of index
     */
    public $isIndex;

    /**
     * is page of content
     * @var boolean
     */
    public $isContent;

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

    public $navs;

    public $activatedNav;

    function glam_ready()
    {
        parent::glam_ready();

        define('GNU_JS', GNU_URL . 'js/');

        $this->glam_board();

        $script = \pathinfo($_SERVER['SCRIPT_NAME'], \PATHINFO_FILENAME);

        $slugs = &$this->_slugs;
        $root = &$slugs[0];

        $this->script = $script;
        $this->isIndex = $script === 'index';

        $cache =& $this->cache;

        // nav
        $cachedNav = $cache->get('navs');
        if (false && $cachedNav) {
            $this->navs = $cachedNav;
        } else {
            $navs = $this->getNavs();
            $flat = [];
            $nested = [];
            $parents = [];

            foreach ($navs as $nav) {
                $id = $nav['id'];
                $depth = $nav['depth'];

                $link = $nav['link'];
                $nav['link'] = $link;
                $nav['parent'] = null;
                $nav['parentLink'] = '/';
                $nav['children'] = [];
                $nav['active'] = false;

                $flat[$id] = $nav;

                $target = &$flat[$id];

                $parents[$depth] = &$target; // &$flat[$id];

                if ($depth) {
                    $parent = &$parents[$depth - 1];

                    $_notSlug = '/^[\/?]/';
                    if (!preg_match($_notSlug, $target['link'])) {
                        $glue = $link[0] === '#' ? '' : '/';
                        if (!preg_match($_notSlug, $parent['link'])) {
                            $target['link'] = $parent['link'] . $glue . $link;
                        } else {
                            $target['link'] = $parent['parentLink'] . $glue . $link;
                        }
                    } else {
                        $target['parentLink'] = $parent['link'];
                    }

                    $target['parent'] = &$parent;
                    $parent['children'][] = &$target;
                } else {
                    $nested[$link] = &$target;
                }
            }

            // fixed target
            foreach ($flat as &$nav) {
                $nav['link'] = $this->_getNavLink($nav);
            }

            $cache->set('navs', $nested);
            $this->navs = $nested;
        }
        $this->setLocation();
        $this->setNavActive();
    }

    protected function setNavActive($navs = null)
    {
        if (!$navs) {
            $navs =& $this->navs;
        }
        $_uri = &$this->_uri;
        $activatedNav = &$this->activatedNav;
        foreach ($navs as &$nav) {
            if ($nav['active']) {
                continue;
            }

            if ($nav['link'] === $_uri) {
                if (!$activatedNav || ($activatedNav['depth'] <= $nav['depth'])) {
                    $activatedNav = $nav;
                }

                $nav['active'] = true;
                $parent = &$nav['parent'];

                while ($parent !== null) {
                    $parent['active'] = true;
                    $parent = &$parent['parent'];
                }
            }
            if ($nav['children']) {
                $this->setNavActive($nav['children']);
            }
        }
    }

    protected function setLocation(): bool
    {
        $script = &$this->script;
        $isIndex = &$this->isIndex;

        global $theme_config;
        $styles = $theme_config['styles'] ?? null;

        if ($isIndex) {
            $this->setLocationIndex();
        } else if ($script === 'router') {
            $this->isContent = true;
            return true;
        } else if (isset($GLOBALS['board'], $GLOBALS['board']['bo_table'])) {
            $this->isBoard = $GLOBALS['board']['bo_table'];
            $styleBoard = $styles['board'] ?? false;
            $this->head->styles->url(10, GLAM_CSS . 'board.css');
            if ($styleBoard) {
                $this->head->styles->url(10, GNU_THEME_CSS . 'board.css');
            }
            return true;
        }
        return false;
    }

    protected function setLocationIndex()
    {
        global $theme_config;
        $styles = $theme_config['styles'] ?? null;
        $styleIndex = $styles['index'] ?? true;
        if ($styleIndex) {
            $this->head->styles->url(10, GNU_THEME_CSS . 'index.css');
        }
        return true;
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

            $locale = $defaultLocale;

            if ($this->isBoard) {
                $prefix = explode('_', $this->isBoard)[0];
                if (isset($fixedLocales[$prefix])) {
                    $locale = $prefix;
                }
            } else {
                $_slugs = &$this->_slugs;
                $slug = $_slugs[0] ?? null;
                if (isset($fixedLocales[$slug])) {
                    $locale = $slug;

                    if (!isset($_slugs[0])) {
                        $this->setLocationIndex();
                    }
                }
            }

            if ($locale) {
                set_session('locale', $locale);
                array_shift($_slugs);

            } elseif (isset($_SESSION['locale'])) {
                $locale = &$_SESSION['locale'];
            }

            $this->locale = $locale;

            define('GNU_LOCALE_URL', GNU_URL . $locale . '/');
            define('GNU_LOCALE_CONTENTS', GNU_CONTENTS . $locale . '/');
        }

        return $this->locale;
    }

    function getBodyClass(): array
    {
        if ($this->isIndex) {
            return ['index'];
        }

        if ($this->isContent) {
            $classes = [];
            $_slugs = &$this->_slugs;
            foreach ($_slugs as $key => $value) {
                $classes[] = implode('-', array_slice($_slugs, 0, $key + 1));
            }
            return $classes;
        }

        if ($this->isBoard) {
            return [$this->isBoard];
        }

        return [];
    }


    function bodyClass(): string
    {
        $classList = $this->getBodyClass();

        return trim($this->_bodyClass . ' page_' . implode(' page_', $classList));
    }

    function setBodyClass(string $className)
    {
        $this->_bodyClass .= ' ' . $className;
    }

    function navList($root = null, $options = [])
    {
        $options += [
            'title' => 0,
            'children' => true
        ];

        $optionsChildren = &$options['children'];

        if (!$root) {
            $root = [
                'name' => '메뉴',
                'children' => $this->navs
            ];
        }

        $navs = $root['children'];

        $html = [];

        if ($options['title']) {
            $hn = 'h' . $options['title'];
            $html[] = "<{$hn}>{$root['name']}</{$hn}>";
            $options['title'] = false;
        }

        $html[] = '<ul>';

        foreach ($navs as $nav) {
            $id = $nav['id'];

            $href = GNU_URL . ltrim($nav['link'], '/');
            $name = $nav['name'];

            $classList = [];

            if ($nav['active']) {
                $classList[] = 'dot-nav_active';
                $name = '<b>' . $name . '</b>';
            }

            if ($nav['class']) {
                $classList[] = $nav['class'];
            }

            $classList = $classList ?
                ' class="' . implode(' ', $classList) . '"' :
                '';

            $html[] = '<li data-id="' . $id . '"' . $classList . '>';
            $html[] = '<a href="' . $href . '"><span>' . $name . '</span></a>';
            if ($optionsChildren && $nav['children']) {
                $html[] = $this->navList($nav);
            }
            $html[] = '</li>';
        }

        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }

    function activatedNavList()
    {
        return $this->navList($this->activatedNav['parent']);
    }

    function activeNav($depth = 1, string $index = null): array
    {
        if (!is_numeric($depth)) {
            $index = $depth;
            $depth = 1;
        }

        $nav = $this->activatedNav;
        if ($nav) {
            while ($nav['parent'] && $nav['depth'] > $depth) {
                $nav = $nav['parent'];
            }
        }

        return $nav;
    }

    function activeNavList(int $depth = 1, array $options = [])
    {
        return $this->navList($this->activeNav($depth - 1), $options);
    }

    function activeNavDepth($depth = 1)
    {
        $depth--;
        $activatedNav = $this->activatedNav;
        if ($activatedNav && $activatedNav['depth'] >= $depth) {
            while ($activatedNav['depth'] > $depth && $activatedNav['parent']) {
                $activatedNav = $activatedNav['parent'];
            }
            return $activatedNav['children'];
        }

        return false;
    }

    function navCrumb()
    {
        $nav = $this->activatedNav;
        if ($nav) {
            $navs = [$nav];
            while ($nav['parent']) {
                $navs[] = $nav['parent'];
                $nav = $nav['parent'];
            }
            $navs = array_reverse($navs);
            return $this->navList(['children' => $navs], [
                'children' => false
            ]);
        }
        return '';
    }

    protected function _getNavLink(array $nav)
    {
        if ($nav['target'] === 'child') {
            if (count($nav['children'])) {
                $child = $nav['children'][0];
                return $child['target'] === 'child' ?
                    $this->_getNavLink($child) :
                    $child['link'];
            }
        }
        return $nav['link'] ?? '#';
    }
}