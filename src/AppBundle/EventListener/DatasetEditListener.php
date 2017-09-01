<?

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\Dataset;
use AppBundle\Entity\DatasetEdit;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Listen for Doctrine onFlush events and record the user who created or 
 * edited a dataset
 *
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
 *
 */
class DatasetEditListener
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage = null) 
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * using the onFlush event for both new and existing datasets,
     * since the preUpdate event (which could be used for existing datasets)
     * does not allow changes to entity associations
     */
    public function onFlush(onFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Dataset) {
                $this->recordEdit($entity, $em, $uow, false);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Dataset) {
                $this->recordEdit($entity, $em, $uow, true);
            }
        }
    }


    public function recordEdit($dataset, $em, $uow, $entityAlreadyExists = false)
    {
        $currentUser = $this->getUser();
        $edit = new DatasetEdit();
        $edit->setTimestamp(new \DateTime('now'));
        $edit->setParentDatasetUid($dataset);
        $edit->setUser($currentUser->getUsername());
        if ($entityAlreadyExists) {
            $edit->setEditType("updated");
        } else {
            $edit->setEditType("created");
        }
        $em->persist($edit);
        $dataset->addDatasetEdits($edit);
        $md = $em->getClassMetadata('AppBundle:DatasetEdit');
        $uow->computeChangeSet($md, $edit);


        return $dataset;
    }


    public function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

}
