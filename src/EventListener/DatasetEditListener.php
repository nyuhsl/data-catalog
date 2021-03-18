<?php

namespace App\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use App\Entity\Dataset;
use App\Entity\DatasetEdit;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Listen for Doctrine onFlush events and record info about a dataset edit
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

    public function __construct(TokenStorageInterface $tokenStorage = null) 
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
        $changeset = $uow->getEntityChangeset($dataset);
        $currentUser = $this->getUser();
        $edit = new DatasetEdit();
        $edit->setTimestamp(new \DateTime('now'));
        $edit->setParentDatasetUid($dataset);
        $edit->setUser($currentUser->getUsername());

        // check if we are archiving or unarchiving this dataset
        if (array_key_exists('archived', $changeset)) {
            $changes = $changeset['archived'];
            $previousValue = array_key_exists(0, $changes) ? $changes[0] : null;
            $newValue = array_key_exists(1, $changes) ? $changes[1] : null;
            if ($previousValue == false && $newValue != false) {
                // this means we are archiving it
                $edit->setEditType("archived");
            } elseif ($previousValue == true && $newValue != true) {
                // this means we are unarchiving it
                $edit->setEditType("unarchived");
            } elseif (!$entityAlreadyExists) {
                // if we're setting the 'archived' value on initial dataset entry
                $edit->setEditType("created");
            }
        // if 'archived' isn't being set in this call, we're almost certainly updating an existing entry
        } elseif ($entityAlreadyExists) {
            $edit->setEditType("updated");
        // if not, must be a brand new one
        } 

        // record any notes on this edit
        if (array_key_exists('archival_notes', $changeset)) {
            $edit->setEditNotes($changeset['archival_notes'][1]);
        } elseif (array_key_exists('last_edit_notes', $changeset)) {
            $edit->setEditNotes($changeset['last_edit_notes'][1]);
        }

        $em->persist($edit);
        $dataset->addDatasetEdits($edit);
        $md = $em->getClassMetadata('App:DatasetEdit');
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

