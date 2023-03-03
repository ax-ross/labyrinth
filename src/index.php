<?php

use App\Classes\LabyrinthController;
use App\Classes\RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

$requestHandler = new RequestHandler($_REQUEST, $_SERVER);
$requestHandler->registerRoute('/', new LabyrinthController());
$requestHandler->handle();