<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/** 
 * Simple Doctrine repository for Dataset entity
 *
 *   This file is part of the Data Catalog project.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
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