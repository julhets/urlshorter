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

//        $resources->get('', 'resources.customer:all')
//            ->assert('websiteId', '\d+');
//
//        $resources->get('/customers', 'resources.customer:allByWebsite')
//            ->assert('websiteId', '\d+');
//
//        $resources->get('/customers/{id}', 'resources.customer:one')
//            ->assert('id', '\d+');
//
//        $resources->get('{storeId}/customers', 'resources.customer:allByStore')
//            ->assert('websiteId', '\d+')
//            ->assert('storeId', '\d+');
//
//        $resources->get('/customers/{id}', 'resources.customer:oneByWebsite')
//            ->assert('websiteId', '\d+')
//            ->assert('id', '\d+');
//
//        $resources->get('{storeId}/customers/{id}', 'resources.customer:oneByStore')
//            ->assert('websiteId', '\d+')
//            ->assert('storeId', '\d+')
//            ->assert('id', '\d+');
//
//        $resources->post('/customers', 'resources.customer:createByWebsite')
//            ->assert('websiteId', '\d+');
//
//        $resources->put('/customers/{id}', 'resources.customer:updateByWebsite')
//            ->assert('websiteId', '\d+')
//            ->assert('id', '\d+');

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
