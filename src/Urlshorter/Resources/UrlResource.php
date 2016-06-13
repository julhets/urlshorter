<?php

namespace Urlshorter\Resources;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Urlshorter\Repository\UrlRepositoryInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class UrlResource
{
  /**
   * @var UrlRepository
   */
  private $repository;

  public function __construct(
      UrlRepositoryInterface $repository
  )
  {
    $this->repository = $repository;
  }

  public function getUrlRedirectById(
      Application $app,
      Request $request,
      $id
  )
  {
    $urlToRedirect = null;
    try {
      $url = $this->repository->findOne($id);
      if ($url) {
        $url->setHits($url->getHits() + 1);
        $this->repository->persist($url);
      }
    } catch (\Exception $exception) {
      return $app->abort(500);
    }

    if (!$url) {
      return $app->abort(404);
    }
    return $app->redirect($url->getUrl(), 301);
  }

  public function getUrlRedirectByShortUrlCode(
      Application $app,
      Request $request,
      $shortUrlCode
  )
  {
    if ($shortUrlCode == 'stats') {
      //caso o endpoint for "stats", fazer um redirect interno -
      $url = $request->getUriForPath('/stats/all');
      $subRequest = Request::create($url, 'GET');
      $response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
      return $response;
    }
    $urlToRedirect = null;
    try {
      $url = $this->repository->findByShortUrlCode($shortUrlCode);
      if ($url) {
        $url->setHits($url->getHits() + 1);
        $this->repository->persist($url);
      }
    } catch (\Exception $exception) {
      return $app->abort(500);
    }

    if (!$url) {
      return $app->abort(404);
    }
    return $app->redirect($url->getUrl(), 301);
  }

  public function deleteUrl(Application $app,
                            Request $request,
                            $id)
  {
    try {
      $this->repository->remove($id);
      exit;
    } catch (\Exception $exception) {
      return $app->abort(500);
    }
  }
}
