<?php
require_once('../library/Mnl/Loader.php');
require_once('../library/Mnl/Loader/Paths.php');
$loader = new Mnl\Loader();
$loader->registerAutoload();

$loader->registerPath(
    '../library/'
);

