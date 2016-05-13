<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;


class DatasetRepository extends EntityRepository
{
    public function countAllUnpublished()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(d) FROM AppBundle:Dataset d WHERE d.published=false'
            )
            ->getSingleScalarResult();
    }

    public function findAllUnpublished() {
      return $this->getEntityManager()
        ->createQuery(
          'SELECT d FROM AppBundle:Dataset d WHERE d.published=false'
        )
        ->getResult();
    }


    public function getNewDatasetId() {
      $newId = $this->getEntityManager()
        ->createQuery(
          'SELECT max(d.dataset_uid) + 1 FROM AppBundle:Dataset d'
        )
        ->getSingleScalarResult();

      return $newId;

    }


}
