<?php
/**
 * Created by PhpStorm.
 * User: julhets
 * Date: 6/11/16
 * Time: 5:47 PM
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;

$app = new Application();

define('APP_ENV', getenv('APP_ENV') ?: 'dev');
$app['debug'] = APP_ENV == 'dev' ?: false;
//require __DIR__ . '/' . APP_ENV . '/orm-config.php';

$app['config'] = $app->share(function () {
  $configDir = __DIR__ . '/' . APP_ENV . '/*.php';

  $configBag = [];
  foreach (glob($configDir) as $config) {
    $namespace = preg_replace(
        '/.php|\//',
        '',
        substr($config, strrpos($config, '/'), strlen($config))
    );

    $configBag[$namespace] = require_once $config;
  }
  $configBag['orm-config'] = require_once 'orm-config.php';

  return $configBag;
});

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $app['config']['db']
]);

//ORM config
$app->register(new \Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, $app['config']['orm-config']);
$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['orm.em']->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['orm.em']),
));
//end

$em = $app['orm.em'];

if ($app['debug']) {
  $app->register(new Silex\Provider\HttpFragmentServiceProvider());
  $app->register(new Silex\Provider\TwigServiceProvider());
  $app->register(new Silex\Provider\UrlGeneratorServiceProvider());

  $app->register(new  Silex\Provider\WebProfilerServiceProvider(), [
      'profiler.cache_dir' => '/tmp/urlshorter_profiler',
  ]);

  $app->register(new Sorien\Provider\DoctrineProfilerServiceProvider());
}

$app->error(function (\Exception $exception, $code) use ($app) {
  if ($app['debug']) {
    return;
  }

  return $app->json(
      [
          'status' => $code,
          'message' => $exception->getMessage()
      ],
      $code
  );
});

//$url = new Urlshorter\ValueObject\Url();
//$url->setCode('code');
//$url->setHits(10);
//$url->setShortUrl('shortUrl');
//$url->setUrl('url');
//$url->setUser(20);
//$em->persist($url);
//$em->flush();

return $app;