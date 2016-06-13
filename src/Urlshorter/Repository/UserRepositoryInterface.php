<?php

namespace Urlshorter\Repository;

use Urlshorter\ValueObject\User;

interface UserRepositoryInterface
{
  public function findOne($id);

  public function persist(User $user);

  public function remove($id);
}
