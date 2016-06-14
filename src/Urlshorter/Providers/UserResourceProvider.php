<?php

namespace Urlshorter\Providers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Urlshorter\Resources\UserResource;
use Urlshorter\Repository\UserRepository;

class UserResourceProvider extends CommonResourceProvider implements
    ServiceProviderInterface,
    ControllerProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['repository.user'] = $app->share(function (Application $app) {
            return new UserRepository($app['orm.em']);
        });

        $app['resources.user'] = $app->share(function (Application $app) {
            return new UserResource(
                $app['repository.user'],
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

        //POST /users
        $resources->post('/users', 'resources.user:persistUser');
        $resources->post('/users/', 'resources.user:persistUser');

        //POST /users/:userid/urls
        $resources->post('/users/{id}/urls', 'resources.user:persistUrl')
            ->assert('id', '\d+');

        //GET /users/:userId/stats
        $resources->get('/users/{id}/stats', 'resources.user:getUserUrlsStats')
            ->assert('id', '\d+');

        //DELETE /user/:userId
        $resources->delete('/user/{id}', 'resources.user:deleteUser')
            ->assert('id', '\d+');

        //GET /stats
        $resources->get('/stats/all', 'resources.user:getAllUrlsStats');

        //GET /stats/:id
        $resources->get('/stats/{id}', 'resources.user:getUrlStats')
            ->assert('id', '\d+');
        return $resources;
    }
}
