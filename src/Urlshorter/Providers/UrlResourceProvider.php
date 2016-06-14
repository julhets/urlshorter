<?php

namespace Urlshorter\Providers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Urlshorter\Resources\UrlResource;
use Urlshorter\Repository\UrlRepository;

class UrlResourceProvider extends CommonResourceProvider implements
    ServiceProviderInterface,
    ControllerProviderInterface
{

  /**
   * @param Application $app
   */
  public function register(Application $app)
  {
    $app['repository.url'] = $app->share(function (Application $app) {
      return new UrlRepository($app['orm.em']);
    });

    $app['resources.url'] = $app->share(function (Application $app) {
      return new UrlResource(
          $app['repository.url']
      );
    });
  }

  /**
   * @param Application $app
   * @return mixed
   */
  public function connect(Application $app)
  {
    $resources = $app['controllers_factory'];

    //GET /urls/:id
    $resources->get('/urls/{id}', 'resources.url:getUrlRedirectById')
        ->assert('id', '\d+');

    //GET /shortUrlCode
    $resources->get('{shortUrlCode}', 'resources.url:getUrlRedirectByShortUrlCode');
    $resources->get('{shortUrlCode}/', 'resources.url:getUrlRedirectByShortUrlCode');

    //DELETE /urls/:id
    $resources->delete('/urls/{id}', 'resources.url:deleteUrl')
        ->assert('id', '\d+');

    return $resources;
  }
}
