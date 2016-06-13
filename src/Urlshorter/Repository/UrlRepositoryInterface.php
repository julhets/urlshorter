<?php

namespace Urlshorter\Repository;

use Urlshorter\ValueObject\Url;

interface UrlRepositoryInterface
{
  public function findOne($id);

  public function persist(Url $url);

  public function remove($id);
}
