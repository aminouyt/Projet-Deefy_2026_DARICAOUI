<?php

require_once 'vendor/autoload.php';

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\dispatch\Dispatcher;

session_start();

DeefyRepository::setConfig(__DIR__ . '/src/config/deefy.db.ini');

$dispatcher = new Dispatcher();
$dispatcher->run();
