<?php
require '../../_common.php';

$orders = $glam->getRentalOrders();

require '../../head.php';
require './orders.phtml';
require '../../tail.php';