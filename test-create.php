<?php

require_once "./Database.php";
require_once "./ORMInterface.php";
require_once "./ORM.php";
require_once "./model.php";

$db = new Database();
$orm = new ORM($db);

$user = new User('Grundrak', 'test@email.com', '123456' );

$orm->create($user);
