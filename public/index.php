<?php

$app = require_once __DIR__ . '/../config/cli-config.php';

use Urlshorter\Providers\UrlResourceProvider;
use Urlshorter\Providers\UserResourceProvider;
use Silex\Application;

$app->get('/', function (Application $app) {
  return $app->json(['urlshorter' => 'Welcome to UrlShorter API.']);
});

//URL
$app->register($urlProvider = new UrlResourceProvider());
$app->mount('/', $urlProvider);

//USER & STATS
$app->register($userProvider = new UserResourceProvider());
$app->mount('/', $userProvider);

$app->run();
