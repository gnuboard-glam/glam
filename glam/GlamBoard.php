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
        'login' => '회원 인증',
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

    public $navs;

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


        $cache =& $this->cache;

        // nav
        $cachedNav = $cache->get('navs');
        if ($cachedNav) {
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
                $nav['parentLink'] = '/';
                $nav['children'] = [];

                $flat[$id] = $nav;

                $target = &$flat[$id];

                $parents[$depth] = &$target; // &$flat[$id];

                if ($depth) {
                    $parent = &$parents[$depth - 1];

                    $_notSlug = '/^[\/#\?]/';
                    if (!preg_match($_notSlug, $target['link'])) {
                        if (!preg_match($_notSlug, $parent['link'])) {
                            $target['link'] = $parent['link'] . '/' . $link;
                        } else {
                            $target['link'] = $parent['parentLink'] . '/' . $link;
                        }
                    } else {
                        $target['parentLink'] = $parent['link'];
                    }

                    $parent['children'][] = &$target;
                } else {
                    $nested[$link] = &$target;
                }
            }

            // fixed target
            foreach ($flat as &$nav) {
                if ($nav['target'] === 'child') {
                    if (count($nav['children'])) {
                        $nav['link'] = $nav['children'][0]['link'];
                    }
                    $nav['target'] = 'self';
                }
            }

            $cache->set('navs', $nested);
            $this->navs = $nested;
        }
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

            define('GNU_LOCALE_CONTENTS', GNU_CONTENTS . $locale . '/');
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

    function nav($root = null)
    {
        $navs = $root ?
            $root['children'] :
            $this->navs;

        $html = ['<ul>'];

        foreach ($navs as $nav) {
            $id = $nav['id'];
            $href = GNU_URL . $nav['link'];
            $html[] = '<li data-id="' . $id . '">';

            $html[] = '<a href="' . $href . '">' . $nav['name'] . ' </a>';
            if ($nav['children']) {
                $html[] = $this->nav($nav);
            }
            $html[] = '</li>';
        }

        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }
}