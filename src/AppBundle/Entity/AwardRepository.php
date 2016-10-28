<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;


class AwardRepository extends EntityRepository
{
    public function findAllOrdered() {
      return $this->getEntityManager()
        ->createQuery(
          'SELECT a FROM AppBundle:Award a ORDER BY a.award ASC'
        )
        ->getResult();
    }


}
