<?php

namespace Urlshorter\Repository;

use Urlshorter\ValueObject\Url;

class UrlRepository implements UrlRepositoryInterface
{
  use RepositoryPaginable;

  private $em;

  public function __construct(\Doctrine\ORM\EntityManager $em)
  {
    $this->em = $em;
  }

  public function findOne($id)
  {
    return $this->em->find('\Urlshorter\ValueObject\Url', $id);
  }

  public function findAll()
  {
    return $this->em->getRepository('\Urlshorter\ValueObject\Url')->findBy([], array('hits' => 'DESC'));
  }

  public function findByShortUrlCode($shortUrlCode)
  {
    return $this->em->getRepository('\Urlshorter\ValueObject\Url')->findOneBy(array('shortUrl' => $shortUrlCode));
  }

  public function persist(Url $url)
  {
    try {
      $this->em->persist($url);
      $this->em->flush();
    } catch (\Exception $exception) {
      throw $exception;
    }

    return $url->getId();
  }

  public function remove($id)
  {
    try {
      $this->em->remove($this->findOne($id));
      $this->em->flush();
      return $id;
    } catch (\Exception $exception) {
      throw $exception;
    }
  }
}
