<?php

require_once "./Database.php";
require_once "./ORMInterface.php";
require_once "./ORM.php";
require_once "./model.php";

$db = new Database();
$orm = new ORM($db);

$product = new Product('Produit XXX', 'description testXX   .', 1000.99, 10);

$orm->create($product);
