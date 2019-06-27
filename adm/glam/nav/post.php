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
$targets = $_POST['targets'] ?? [];

$db =& $glam->db;
$table =& $glam->_tableNav;

$removes = [];

$codes = [];
$orders = [];

$counts = [];
$lastDepth = 0;
$order = 0;

foreach ($depths as $id => $depth) {

    if ($lastDepth > $depth) {
        if ($lastDepth) {
            $counts[$lastDepth] = 9;
        }
    } else if (!isset($counts[$depth])) {
        $counts[$depth] = 9;
    }

    $counts[$depth]++;
    $code = implode('', array_slice($counts, 0, $depth + 1));

    $lastDepth = $depth;

    $codes[$id] = $code;
    $orders[$id] = $order++;
}

foreach ($updates as $index => $id) {
    $insert = false;
    if ($id >= 10000000) {
        $insert = true;
    }

    $depth = $depths[$id];
    if ($depth == -1) {
        $removes[] = $id;
        unset($depths[$id]);
        continue;
    }

    $name = $names[$id] ?? 'Untitled';
    $class = $classes[$id] ?? '';
    $icon = $icons[$id] ?? '';
    $use = $uses[$id] ? '1' : '0';

    $link = $links[$id] ?? '#';
    $target = $targets[$id] ?? 'self';

    $name = implode('|||', [$name, $class, $icon]);

    $code = $codes[$id];
    $order = $orders[$id];

    $values = [
        'me_name' => $name,
        'me_link' => $link,
        'me_use' => $use,
        'me_target' => $target,
        'me_code' => $code,
        'me_order' => $order
    ];

    if ($insert) {
        $db->inserted($table, $values);
    } else {
        $db->updated(
            $table,
            $values,
            $id,
            'me_id'
        );
    }
}

if ($removes) {
    $db->deleted($table, $removes, 'me_id');
}

$glam->back();