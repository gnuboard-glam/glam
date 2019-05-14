<?php

namespace Glam;
/**
 * @global $glam GlamAdmin
 */

require '../../_common.php';

$updates = $_POST['updates'] ?? [];
$depths = $_POST['depths'] ?? [];
$names = $_POST['names'] ?? [];
$classes = $_POST['classes'] ?? [];
$icons = $_POST['icons'] ?? [];
$uses = $_POST['uses'] ?? [];

$links = $_POST['links'] ?? [];

$db =& $glam->db;
$table =& $glam->_tableNav;

foreach ($updates as $id) {
    // $depth = $update[$id] ?? 0;
    $name = $names[$id] ?? 'Untitled';
    $class = $classes[$id] ?? '';
    $icon = $icons[$id] ?? '';
    $use = $uses[$id] ? 1 : 0;

    $link = $links[$id] ?? '#';

    $name = implode('|||', [$name, $class, $icon]);

    $db->updated($table, [
        'me_name' => $name,
        'me_link' => $link,
        'me_use' => $use,
    ]);
}

$glam->back();