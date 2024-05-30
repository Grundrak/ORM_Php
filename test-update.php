<?php

require_once "./Database.php";
require_once "./ORMInterface.php";
require_once "./ORM.php";
require_once "./model.php";

$db = new Database();
$orm = new ORM($db);

$product = new Product('9ar3a dyal lma', '9r3a dyal lma chri ashbi dghya .', 2000, 50);

$orm->update($product, "id=1");
