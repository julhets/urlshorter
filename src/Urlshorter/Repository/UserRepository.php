<?php

namespace Urlshorter\Repository;

use Urlshorter\ValueObject\User;

class UserRepository implements UserRepositoryInterface
{
  use RepositoryPaginable;

  private $em;

  public function __construct(\Doctrine\ORM\EntityManager $em)
  {
    $this->em = $em;
  }

  public function findOne($id)
  {
    return $this->em->find('\Urlshorter\ValueObject\User', $id);
  }

  public function persist(User $user)
  {
    try {
      $this->em->persist($user);
      $this->em->flush();
    } catch (\Exception $exception) {
      throw $exception;
    }

    return $user->getId();
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
