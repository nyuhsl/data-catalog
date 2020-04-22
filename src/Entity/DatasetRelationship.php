<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Define relationships among datasets
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
 *
 * @ORM\Entity
 * @ORM\Table(name="dataset_relationships")
 */
class DatasetRelationship {
  /**
   * @ORM\Column(type="integer",name="relationship_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $relationship_attributes;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $relationship_notes;

  /**
   * @ORM\Column(type="integer")
   */
  protected $related_dataset_uid;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="related_datasets")
   * @ORM\JoinColumn(name="parent_dataset_uid",referencedColumnName="dataset_uid")
   */
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
     * Set relationship_attributes
     *
     * @param string $relationshipAttributes
     * @return DatasetRelationship
     */
    public function setRelationshipAttributes($relationshipAttributes)
    {
        $this->relationship_attributes = $relationshipAttributes;

        return $this;
    }

    /**
     * Get relationship_attributes
     *
     * @return string 
     */
    public function getRelationshipAttributes()
    {
        return $this->relationship_attributes;
    }

    /**
     * Set relationship_notes
     *
     * @param string $relationshipNotes
     * @return DatasetRelationship
     */
    public function setRelationshipNotes($relationshipNotes)
    {
        $this->relationship_notes = $relationshipNotes;

        return $this;
    }

    /**
     * Get relationship_notes
     *
     * @return string 
     */
    public function getRelationshipNotes()
    {
        return $this->relationship_notes;
    }

    /**
     * Set related_dataset_uid
     *
     * @param integer $relatedDatasetUid
     * @return DatasetRelationship
     */
    public function setRelatedDatasetUid($relatedDatasetUid)
    {
        $this->related_dataset_uid = $relatedDatasetUid;

        return $this;
    }

    /**
     * Get related_dataset_uid
     *
     * @return integer 
     */
    public function getRelatedDatasetUid()
    {
        return $this->related_dataset_uid;
    }

    /**
     * Set parent_dataset_uid
     *
     * @param \App\Entity\Dataset $parentDatasetUid
     * @return DatasetRelationship
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

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
        return array(
            'related_dataset_uid'=>$this->related_dataset_uid,
            'relationship_attributes'=>$this->relationship_attributes,
            'relationship_notes'=>$this->relationship_notes,
            'parent_dataset_uid'=>$this->parent_dataset_uid
        );
    }
}
