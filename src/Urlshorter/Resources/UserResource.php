<?php

namespace Urlshorter\Resources;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\Url;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Urlshorter\Repository\UrlRepositoryInterface;
use Urlshorter\Repository\UserRepositoryInterface;
use Urlshorter\ValueObject\User;

class UserResource
{
  /**
   * @var UserRepository
   */
  private $repository;
  private $urlRepository;

  public function __construct(
      UserRepositoryInterface $repository,
      UrlRepositoryInterface $urlRepository
  )
  {
    $this->repository = $repository;
    $this->urlRepository = $urlRepository;
  }

  public function persistUser(
      Application $app,
      Request $request
  )
  {
    try {
      $data = $request->request->all();

      $user = new User();
      $user->bind($data);
      $user->assert();
      $userExists = $this->repository->findOne($user->getId());
      if ($userExists) {
        throw new ValidationException();
      }
      $userId = $this->repository->persist($user);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500, $exception->getMessage());
    }

    return $app->json(['id' => $userId], 201);
  }

  public function persistUrl(Application $app,
                             Request $request,
                             $id)
  {
    try {
      $user = $this->repository->findOne($id);
      if (!$user) {
        //user not founded
        throw new ValidationException('User not founded.');
      }
      $data = $request->request->all();
      if (!$data) {
        //user not founded
        throw new ValidationException('No parameters on request.');
      }

      $url = new \Urlshorter\ValueObject\Url();
      $url->bind($data);

      $url->setShortUrl(substr(md5($data['url']), 0, 12));
      $url->setUser($user);
      $url->setHits(0);
      $url->setBasePath($request->getSchemeAndHttpHost());

      $url->assert();
      $urlId = $this->urlRepository->persist($url);
      return $app->json($url->jsonSerialize(), 201);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500, $exception->getMessage());
    }
  }

  public function deleteUser(Application $app,
                             Request $request,
                             $id)
  {
    try {
      $user = $this->repository->findOne($id);
      if (!$user) {
        //user not founded
        throw new ValidationException('User not founded.');
      }
      $this->repository->remove($id);
      exit;
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500);
    }
  }

  public function getUserUrlsStats(Application $app,
                                   Request $request,
                                   $id)
  {
    try {
      $user = $this->repository->findOne($id);
      if (!$user) {
        //user not founded
        throw new ValidationException('User not founded.');
      }

      $basePath = $request->getSchemeAndHttpHost();
      $urls = $user->getUrls();
      $totalHits = 0;
      $urlCount = count($urls);
      $topUrls = array();
      foreach ($urls as $url) {
        $url->setBasePath($basePath);
        $totalHits = $totalHits + $url->getHits();
        $topUrls[] = $url;
      }

      $topUrls = array_slice($topUrls, 0, 10);

      $responseObject = array();
      $responseObject['hits'] = $totalHits;
      $responseObject['urlCount'] = $urlCount;
      $responseObject['topUrls'] = $topUrls;

      return $app->json($responseObject);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500);
    }
  }

  public function getAllUrlsStats(Application $app,
                                  Request $request)
  {
    try {
      $basePath = $request->getSchemeAndHttpHost();
      $urls = $this->urlRepository->findAll();
      $totalHits = 0;
      $urlCount = count($urls);
      $topUrls = array();
      foreach ($urls as $url) {
        $url->setBasePath($basePath);
        $totalHits = $totalHits + $url->getHits();
        $topUrls[] = $url;
      }

      $topUrls = array_slice($topUrls, 0, 10);

      $responseObject = array();
      $responseObject['hits'] = $totalHits;
      $responseObject['urlCount'] = $urlCount;
      $responseObject['topUrls'] = $topUrls;

      return $app->json($responseObject);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500);
    }
  }

  public function getUrlStats(Application $app,
                              Request $request,
                              $id)
  {
    try {
      $url = $this->urlRepository->findOne($id);
      if (!$url) {
        //url not founded
        throw new ValidationException('Url not founded.');
      }

      $basePath = $request->getSchemeAndHttpHost();
      $url->setBasePath($basePath);
      return $app->json($url);
    } catch (ValidationException $exception) {
      return $app->abort(409, $exception->getMessage());
    } catch (\Exception $exception) {
      return $app->abort(500);
    }
  }
}
