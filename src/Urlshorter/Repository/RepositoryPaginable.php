<?php

namespace Urlshorter\Repository;

use Doctrine\DBAL\Query\QueryBuilder;

trait RepositoryPaginable
{
    private $offset = 0;
    private $limit = 10;

    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    protected function paginate(QueryBuilder $query)
    {
        $query->setFirstResult($this->offset)
            ->setMaxResults($this->limit);
    }
}
