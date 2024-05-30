<?php

require_once "./Database.php";
require_once "./ORMInterface.php";
require_once "./ORM.php";
require_once "./model.php";

$db = new Database();
$orm = new ORM($db);

$tableName = "products";
$condition = 'id = ?';
$param = [
    'types' => 'i', 
    'values' => [5]
];

try {
    $product = $orm->fetch($tableName, $condition, $param);
    if ($product) {
        echo "Product found: " . print_r($product, true);
    } else {
        echo "No product found with the specified ID.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
