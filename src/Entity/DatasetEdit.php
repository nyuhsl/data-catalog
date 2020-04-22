<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Entity to track edits made to datasets
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
 * @ORM\Entity(repositoryClass="App\Entity\DatasetEditRepository")
 * @ORM\Table(name="dataset_edits")
 */
class DatasetEdit {

  /**
   * @ORM\Column(type="integer",name="edit_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;


  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $user;


  /**
   * @ORM\Column(type="datetime")
   */
  protected $timestamp;


  /**
   * @ORM\Column(type="string", length=64)
   */
  protected $edit_type;


  /**
   * @ORM\Column(type="string", length=500, nullable=true)
   */
  protected $edit_notes;


  /**
   * @ORM\ManyToOne(targetEntity="Dataset", inversedBy="dataset_edits")
   * @ORM\JoinColumn(name="parent_dataset_uid", referencedColumnName="dataset_uid")
   **/
  protected $parent_dataset_uid;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return DatasetEdit
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set timestamp
     *
     * @param string $timestamp
     * @return DatasetEdit
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return string 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set edit type
     *
     * @param string $editType
     * @return DatasetEdit
     */
    public function setEditType($editType)
    {
        $this->edit_type = $editType;

        return $this;
    }

    /**
     * Get edit type
     *
     * @return string 
     */
    public function getEditType()
    {
        return $this->edit_type;
    }


    /**
     * Set edit notes
     *
     * @param string $editNotes
     * @return DatasetEdit
     */
    public function setEditNotes($editNotes)
    {
        $this->edit_notes = $editNotes;

        return $this;
    }


    /**
     * Get edit notes
     *
     * @return string 
     */
    public function getEditNotes()
    {
        return $this->edit_notes;
    }


    /**
     * Set parent_dataset_uid
     *
     * @param \App\Entity\Dataset $parentDatasetUid
     * @return DatasetEdit
     */
    public function setParentDatasetUid(\App\Entity\Dataset $parentDatasetUid = null)
    {
        $this->parent_dataset_uid = $parentDatasetUid;

        return $this;
    }


    /**
     * Get parent_dataset_uid
     *
     * @return \App\Entity\Dataset 
     */
    public function getParentDatasetUid()
    {
        return $this->parent_dataset_uid;
    }

}
