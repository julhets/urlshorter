<?php

//$whitelistIp = [
//    '127.0.0.1'
//];
//if (!in_array(
//    $_SERVER['REMOTE_ADDR'],
//    $whitelistIp
//)
//) {
//  header('HTTP/1.1 403 Forbidden');
//  die('You are not Allowed!');
//}

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
