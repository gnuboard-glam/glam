<?php

namespace Glam;

abstract class Glam
{
    const VERSION = 2;

    /**
     * @var {GlamBoard|GlamAdmin|GlamShop|GlamShopAdmin|GlamRental|GlamRentalAdmin}
     */
    static $instance = null;

    static function that()
    {
        return self::$instance;
    }

    static function load($options)
    {
        if (!is_array($options)) {
            $options = [
                'included' => $options
            ];
        }

        define('GNU', dirname($options['included']) . '/');

        /**
         * @var {'board'|'shop'|'rental'}
         */
        $solution = $options['solution'] ?? null;

        if ($solution === null) {
            $solution = stream_resolve_include_path(GNU . 'extend/shop.extend.php') === false ?
                'board' :
                'shop';

            $options['solution'] = $solution;
        } else if (!in_array($solution, ['board', 'shop', 'rental'])) {
            die("`{$solution}` is not a supported solution with glam");
        }

        $url = preg_replace(
                '#^' . $_SERVER['DOCUMENT_ROOT'] . '#',
                '',
                str_replace(
                    DIRECTORY_SEPARATOR,
                    '/',
                    dirname($options['included'])
                )
            ) . '/';

        define('GNU_URL', $url);

        $isAdmin = preg_match('#/adm(in)?#', $_SERVER['REQUEST_URI']);

        if ($isAdmin) {
            switch ($solution) {
                case 'shop':
                    $glam = new GlamShopAdmin($options);
                    break;
                case 'rental':
                    $glam = new GlamRentalAdmin($options);
                    break;
                default:
                    $glam = new GlamAdmin($options);
            }
        } else {
            switch ($solution) {
                case 'shop':
                    $glam = new GlamShop($options);
                    break;
                case 'rental':
                    $glam = new GlamRental($options);
                    break;

                default:
                    $glam = new GlamBoard($options);
            }
        }

        self::$instance = $glam;

        return self::$instance;
    }
}