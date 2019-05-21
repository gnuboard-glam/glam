<?php

namespace Glam;

use Dot\Cache\Cache;
use Dot\Cache\CacheFilePool;
use Dot\Dot;
use Dot\Html\Head;
use function Dot\isLocalServer;

const DEFAULT_OPTIONS = [
    'dev' => false,
    'board' => null,
    'useSMS' => false,
    'useCDN' => false
];

abstract class GlamBase
{
    /**
     * @var array
     */
    protected $_slugs;

    protected $_theme;

    /**
     * @var GlamDB
     */
    public $db;

    /**
     * @var GlamSession
     */
    protected $session;

    /**
     * @var Cache
     */
    public $cache;

    /**
     * @var array
     */
    protected $g5;

    /**
     * glam options cache
     * @var array
     */
    protected $_options;

    /**
     * loader options
     * @var array
     */
    protected $options;

    public $_tableConfig = \G5_TABLE_PREFIX . 'config';
    public $_tableOptions = \G5_TABLE_PREFIX . 'glam_options';

    /**
     * @var string
     */
    public $_tableNav = G5_TABLE_PREFIX . 'menu';

    /**
     * @var Head
     */
    public $head;

    final function __construct($options)
    {
        $options += DEFAULT_OPTIONS;

        $uri = $_SERVER['REQUEST_URI'];
        $uri = preg_replace('/[?#].*$/', '', $uri); // remove hash, search
        $uri = preg_replace('#^' . GNU_URL . '#', '', $uri); // remove path
        $uri = rtrim($uri, '/');
        $slugs = explode('/', $uri);

        $this->_slugs = $slugs;

        global $config;

        $theme = 'theme/' . $config['cf_theme'];
        $this->_theme = $theme;

        $this->db = new GlamDB;
        $this->session = new GlamSession;

        $this->cache = new Cache(new CacheFilePool(GNU . 'data/glam-cache'));

        define('GLAM_DEV', isLocalServer() || $options['dev']);
        // move to plugin
        define('GLAM_URL', G5_URL . '/plugin/glam/');
        define('GLAM_THEME', GLAM . 'theme/');
        define('GLAM_SKIN', GLAM . 'skin/');
        define('GLAM_ADMIN', GLAM . 'admin/');
        define('GLAM_ADMIN_URL', GLAM_URL . 'adm/');
        define('GLAM_SHARE', GLAM . 'share/');
        define('GLAM_SHARE_THEME', GLAM_SHARE . 'theme/');
        define('GLAM_SHARE_URL', GNU_URL, 'glam/share');
        define('GLAM_CSS', GLAM_URL . 'css/');
        define('GLAM_JS', GLAM_URL . 'js/');
        define('GLAM_CSSDOT', GLAM_URL . 'cssdot/prebuilt/');
        define('GLAM_JSDOT', GLAM_URL . 'jsdot/');
        define('GNU_THEME', GNU . $theme . '/');
        define('GNU_THEME_URL', GNU_URL . $theme . '/');
        define('GNU_THEME_CSS', GNU_THEME_URL . 'css/');
        define('GNU_THEME_JS', GNU_THEME_URL . 'js/');
        define('GNU_CONTENTS', GNU_THEME . 'contents/');
        define('GNU_TEMPLATES', GNU_THEME . 'templates/');

        global $g5;
        $this->g5 =& $g5;

        $this->glam_ready();
    }

    function glam_ready()
    {
        $this->head = new Head;
    }

    function redirect(string $url)
    {
        Dot::redirect($url);
    }

    function back()
    {
        Dot::back();
    }

    function head()
    {

    }

    function getOptionCacheName(string $name)
    {
        return 'options_' . $name;
    }

    /**
     * @param string $name
     * @param array $defaults
     * @param string $index
     * @return mixed
     */
    function getOption(string $name, $defaults = [], $index = null)
    {
        if (is_string($defaults)) {
            $index = $defaults;
            $defaults = [];
        }

        $options =& $this->_options;

        if (isset($options[$name])) {
            return $index ?
                $options[$name][$index] ?? null :
                $options[$name];
        }

        $cacheName = $this->getOptionCacheName($name);
        $cache = &$this->cache;
        $cached = $cache->get($cacheName);

        if ($cached) {
            $options[$name] = $cached;

            return $index ?
                $cached[$index] ?? null :
                $cached;
        }


        $record = $this->db->selected($this->_tableOptions, 'value', $name, 'name')
            ->result()
            ->fetch();

        if ($record) {
            $values = \unserialize($record['value']);

            if (\count($defaults)) {
                foreach ($defaults as $name => $value) {
                    if (!isset($values[$name]) || $values[$name] === '') {
                        $values[$name] = $value;
                    }
                }
            }

            $options[$name] = $values;
            $cache->set($cacheName, $values);

            return $index ?
                $values[$index] ?? null :
                $values;
        }

        // todo: need this?
        return $index ?
            $defaults[$index] ?? null :
            $defaults;
    }

    function getNavs(array $options = [])
    {
        $options += [
            'all' => false
        ];

        $all = &$options['all'];

        $table = $this->_tableNav;

        $sql = $this->db->select($table)
            ->order('me_order');

        if (!$all) {
            $sql->where('me_use', 1);
        }

        $records = $sql
            ->result()
            ->map([$this, '_setNav']);

        return $records;
    }

    function _setNav($record)
    {
        $code = $record['me_code'];
        $depth = strlen($code) / 2 - 1;

        $values = $record['me_name'];
        $values = explode('|||', $values);
        $name = $values[0];
        $class = $values[1] ?? '';
        $icon = $values[2] ?? '';

        $nav = [
            'id' => $record['me_id'],
            'code' => $code,
            'depth' => $depth,
            'name' => $name,
            'class' => $class,
            'icon' => $icon,
            'link' => $record['me_link'],
            'target' => $record['me_target'],
            'order' => $record['me_order'],
            'use' => $record['me_use'],
            'use2' => $record['me_mobile_use']
        ];

        return $nav;
    }
}