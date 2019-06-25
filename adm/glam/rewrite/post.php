<?php
namespace Glam;
use Dot\File;

/**
* @global $glam GlamAdmin
*/

require '../../_common.php';

$contents = [];

$contents[] = 'RewriteEngine On';

$contents[] = <<<HTACCESS
    # shop index
    #RewriteRule ^shop/index.php$ plugin/glam/shop/index.php [QSA,L]
HTACCESS;

$contents[] = <<<HTACCESS
RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
HTACCESS;

$contents[] = <<<HTACCESS
    # shop sub
    # RewriteRule ^shop/(.*)?$ plugin/glam/shop/index.php?url=$1 [QSA,L]

    # shop naver shopping
    # RewriteRule ^plugin/glam/shop/ep_all.txt$ plugin/glam/shop/ep_all.php [QSA,L]
HTACCESS;

$boardIds = &$glam->getBoardList();
if($boardIds){
    $boardIds = implode('|', $boardIds);
    $contents[] = <<<HTACCESS
    RewriteRule ^({$boardIds})$ bbs/board.php?rewrite=1&bo_table=$1 [QSA,L]
    RewriteRule ^({$boardIds})/([0-9]+)$ bbs/board.php?rewrite=1&bo_table=$1&wr_id=$2 [QSA,L]
    RewriteRule ^({$boardIds})/write$ bbs/write.php?rewrite=1&bo_table=$1 [QSA,L]
HTACCESS;
}

$contents[] = <<<HTACCESS
    # contents
    RewriteRule ^((?!(plugin/glam/)?adm/)[^.]+)$ plugin/glam/router.php?url=$1 [QSA,L]
HTACCESS;

$contents = implode(PHP_EOL, $contents);

File::write(GNU . '.htaccess', $contents);

$glam->back();