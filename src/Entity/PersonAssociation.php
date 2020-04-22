<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Describe a person's relationship to a dataset
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
 * @ORM\Table(name="person_associations")
 */
class PersonAssociation {
  /** 
   * @ORM\Column(type="integer",name="person_association_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $person_association_id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $role;

  /**
   * @ORM\Column(type="boolean",length=128)
   */
  protected $is_corresponding_author = false;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $display_order;

  /**
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="dataset_associations")
   * @ORM\JoinColumn(name="person_id",referencedColumnName="person_id", nullable=FALSE)
   */
  protected $person;
  
  /**
   * @ORM\ManyToOne(targetEntity="Dataset", inversedBy="authorships")
   * @ORM\JoinColumn(name="datasets_dataset_uid",referencedColumnName="dataset_uid", nullable=FALSE)
   */
  protected $dataset;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getPersonAssociationId()
    {
        return $this->person_association_id;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return PersonAssociation
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }


    /**
     * Set is corresponding author
     *
     * @param string $corresponding
     * @return PersonAssociation
     */
    public function setIsCorrespondingAuthor($corresponding)
    {
        $this->is_corresponding_author = $corresponding;

        return $this;
    }

    /**
     * Get is corresponding author
     *
     * @return string 
     */
    public function getIsCorrespondingAuthor()
    {
        return $this->is_corresponding_author;
    }


    /**
     * Set display order
     *
     * @param int $displayOrder
     * @return PersonAssociation
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->display_order = $displayOrder;

        return $this;
    }

    /**
     * Get display_order
     *
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->display_order;
    }


    /**
     * Set person
     *
     * @param \App\Entity\Person $person
     * @return PersonAssociation
     */
    public function setPerson(\App\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \App\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set dataset
     *
     * @param \App\Entity\Dataset $dataset
     * @return PersonAssociation
     */
    public function setDataset(\App\Entity\Dataset $dataset)
    {
        $this->dataset = $dataset;

        return $this;
    }

    /**
     * Get dataset
     *
     * @return \App\Entity\Dataset 
     */
    public function getDataset()
    {
        return $this->dataset;
    }

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
      return array(
        'role'=>$this->role,
        'is_corresponding_author'=>$this->is_corresponding_author,
        'display_order'=>$this->display_order,
        'person'=>$this->person->getDisplayName(),
      );
    }
}
