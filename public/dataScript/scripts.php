<?php
require 'DB.php';

$mysql = new MySQLConnection();
$create = $mysql->createDataBaseIfNotExist();
echo var_dump($create);
