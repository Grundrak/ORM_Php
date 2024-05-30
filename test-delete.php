<?php

require_once "./Database.php";
require_once "./ORMInterface.php";
require_once "./ORM.php";
require_once "./model.php";

$db = new Database();
$orm = new ORM($db);



$tableName = 'products';
$condition = 'id = ?';
$params = [
    'types' => 'i', 
    'values' => [0]
];

$orm->delete($tableName, $condition, $params);
