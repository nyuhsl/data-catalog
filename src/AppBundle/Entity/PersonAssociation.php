<?php
namespace AppBundle\Entity;

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
 * @ORM\Entity
 * @ORM\Table(name="person_associations")
 */
class PersonAssociation {
  /** 
   * @ORM\Column(type="integer",name="person_association_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $role;

  /**
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="dataset_associations")
   * @ORM\JoinColumn(name="person_id",referencedColumnName="person_id")
   */
  protected $person;
  
  /**
   * @ORM\ManyToOne(targetEntity="Dataset", inversedBy="person_associations")
   * @ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $dataset_uid;


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
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     * @return PersonAssociation
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set dataset_uid
     *
     * @param \AppBundle\Entity\Dataset $datasetUid
     * @return PersonAssociation
     */
    public function setDatasetUid(\AppBundle\Entity\Dataset $datasetUid = null)
    {
        $this->dataset_uid = $datasetUid;

        return $this;
    }

    /**
     * Get dataset_uid
     *
     * @return \AppBundle\Entity\Dataset 
     */
    public function getDatasetUid()
    {
        return $this->dataset_uid;
    }
}
