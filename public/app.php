<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

use \Symfony\Component\HttpFoundation\Request;
use \App\AppKernel;

$kernel = new AppKernel();

$request = Request::createFromGlobals();

$response = $kernel->handle($request);

$response->send();