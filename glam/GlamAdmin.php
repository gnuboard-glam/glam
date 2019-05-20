<?php

namespace Glam;

use function Dot\zerofill;

class GlamAdmin extends GlamBase
{
    use GlamBoardTrait;

    public $_adminNavGroups = [];
    public $_adminNavReplaced = [];

    function glam_ready()
    {
        parent::glam_ready();
        $this->glam_board();
    }

    final function setOption(string $name, array $values)
    {
        $record = [];
        foreach ($values as $key => $value) {
            if (\is_numeric($key)) {
                $key = $value;
                $value = $_POST[$key] ?? null;
            }
            $record[$key] = $value;
        }

        $this->db->replaced($this->_tableOptions, [
            'name' => $name,
            'value' => \serialize($record)
        ]);

        $this->cache->out($this->getOptionCacheName($name));
    }

    /**
     * get nav group
     * @param string|int $group
     * @return int|null
     */
    final protected function getNavGroup($group)
    {
        if (\is_numeric($group)) {
            return $group;
        }
        $groups =& $this->_adminNavGroups;

        return $groups[$group] ?? null;
    }

    /**
     * set nav group
     * @param string $group
     * @param int $index
     * @param string $label
     * @return $this
     */
    final protected function setNavGroup(string $group, int $index, string $label = null)
    {
        global $amenu, $menu;

        if (!$label) {
            $label = $group;
        }

        $index = zerofill($index, 3);
        $amenu[$index] = 'admin.menu' . $index . '.php';

        $menu['menu' . $index] = [
            [$index . '000', $label, '#']
        ];

        $this->_adminNavGroups[$group] = $index;

        return $this;
    }

    /**
     * @param $group
     * @param int $order
     * @param string $label
     * @param string $fileName
     * @param null $target
     * @return $this
     */
    final protected function addNav($group, int $order, string $label, string $fileName, &$target = null)
    {
        global $menu;

        $group = $this->getNavGroup($group);

        $className = \strtolower(\ pathinfo($fileName, PATHINFO_FILENAME));
        $className = \str_replace('/', '-', $className);


        if ($fileName !== '#' && \strpos($fileName, \GLAM_ADMIN_URL) !== 0) {
            $fileName = \GLAM_ADMIN_URL . \ltrim($fileName, '/');
        }

        $index = 'menu' . $group;

        if (!isset($menu[$index])) {
            $menu[$index] = [];
        }

        $order = zerofill($order, 3);
        $order = $group . $order;

        $menu[$index][] = [$order, $label, $fileName, $className];

        $target = \end($menu[$index]);

        $this->_adminNavGroups[$order] = &$target;

        return $this;
    }

    final protected function removeNav($group, $index = null)
    {
        global $menu;
        $group = $this->getNavGroup($group);
        $groupIndex = 'menu' . $group;
        if (isset($menu[$groupIndex])) {
            if ($index) {
                $items = $menu[$groupIndex];
                foreach ($items as $key => $item) {
                    if ($item[0] === $group . $index) {
                        unset($items[$key]);
                        break;
                    }
                }
                $menu[$groupIndex] = \array_values($items);
                $_adminNavGroups = &$this->_adminNavGroups;
                if (isset($_adminNavGroups[$group . $index])) {
                    unset($_adminNavGroups[$group . $index]);
                }
            } elseif (isset($menu[$groupIndex])) {
                unset($menu[$groupIndex]);
            }
        }

        return $this;
    }

    final protected function replaceNav($beforeGroup, int $beforeOrder, $group, int $order = null, string $label = null, string $fileName = null)
    {
        global $menu;

        $beforeGroup = $this->getNavGroup($beforeGroup);
        $beforeOrder = zerofill($beforeOrder, 3);
        $before = $beforeGroup . $beforeOrder;

        $beforeItems = $menu['menu' . $beforeGroup];
        $beforeGroupName = $beforeItems[0][1];
        $beforeName = 'unknown';
        $beforeFileName = '#';

        if ($beforeItems) {
            foreach ($beforeItems as $item) {
                if ($item[0] === $before) {
                    $beforeName = $item[1];
                    $beforeFileName = $item[2];
                    break;
                }
            }
        }

        if (!$order) {
            $order = $beforeOrder;
        }

        if (!$label) {
            $label = $beforeName;
        }

        if (!$fileName) {
            $fileName = $beforeFileName;
        }

        $group = $this->getNavGroup($group);
        $order = zerofill($order, 3);
        // $after = $group . $order;
        $afterName = $menu['menu' . $group][0][1] . ' > ' . $label;

        if ($before !== $group) {
            $this->removeNav($beforeGroup, $beforeOrder);
        }

        $this->addNav($group, $order, $label, $fileName, $added);

        $this->_adminNavReplaced[] = [
            'before' => $beforeGroupName . ' > ' . $beforeName,
            'after' => $afterName,
            'href' => $added[2]
        ];

        return $this;
    }

    /**
     * add glam admin menu as dynamic
     * @return $this
     */
    function extendNav()
    {
        global $menu;

        // $this->removeNav('sms');

        $glam = 'glam';
        $this->setNavGroup($glam, '000', 'Glam')
            ->addNav($glam, 10, '도움말', 'glam')
            ->addNav($glam, 20, '메뉴 설정+', 'glam/nav')
            //->addNav($glam, 1, 'Glam', 'glam')
            //->addNav($glam, 5, '바로가기', 'shortcut')
            //->addNav($glam, 10, '동기화', 'sync')
            //->addNav($glam, 15, 'API', 'api')
        ;

        /*

        $this->setNavGroup($bp, 350)
            ->addNav($bp, 1, '게시판+', 'board-category-change')
            ->addNav($bp, 5, '게시판 분류 변경', 'board-category-change')
            ->addNav($bp, 10, '게시글 순서 변경', 'board-article-order');

        $this->addNav('member', '50', '회원 분류명', 'member-label');
        */

        return $this;
    }

    function head()
    {
        echo '<link rel="stylesheet" href="' . \GLAM_CSS . 'global.css?' . rand(0, 100) . '">';
        echo '<script src="' . \GLAM_JS . 'admin/global.js?' . rand(0, 100) . '"></script>';
    }

    function getLocation()
    {
        global $sub_menu, $menu;
        $uri = $_SERVER['REQUEST_URI'];
        $uri = \array_shift(\explode('?', $uri));
        $uri = \rtrim($uri, 'index.php');

        foreach ($menu as $navGroups) {
            foreach ($navGroups as $nav) {
                if (\strpos(\rtrim($nav[2] . '/'), $uri) !== false) {
                    return [
                        $nav[0],
                        $nav[1]
                    ];
                }
            }
        }

        return [null, null];
    }

    function setExtendField(array $values, array $fields)
    {
        foreach ($fields as $name => $rename) {
            if (!\is_numeric($name)) {
                if (isset($values[$rename])) {
                    $values[$name] = $values[$rename];
                    unset($values[$rename]);
                }
            }
        }

        return $values;
    }
}