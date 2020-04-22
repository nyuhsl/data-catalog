<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;



/**
 * An entity for tenporary access keys
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
 * @ORM\Table(name="temp_access_keys")
 */
class TempAccessKey {
  /**
   * @ORM\Column(type="integer",name="tak_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $uuid;

  /**
   * @ORM\Column(type="datetime",length=128,nullable=false)
   */
  protected $generated;

  /**
   * @ORM\Column(type="datetime",nullable=true)
   */
  protected $first_access;
  
  /**
   * @ORM\ManyToOne(targetEntity="Dataset")
   * @ORM\JoinColumn(name="dataset_association",referencedColumnName="dataset_uid", nullable=FALSE)
   */
  protected $dataset_association;

  /**
   * Get name for display
   *
   * @return string
   */

	public function getDisplayName() {
		return $this->full_name;
	}

    /**
     * Constructor
     */
    public function __construct()
    {
      $this->datasetAssociation = new ArrayCollection();
    }

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
     * Set datasetAssociation
     *
     * @param \App\Entity\Dataset $dataset_association
     * @return TempAccessKey
     */
    public function setDatasetAssociation(\App\Entity\Dataset $dataset_association)
    {
        $this->dataset_association = $dataset_association;
        return $this;
    }

    /**
     * Get datasetAssociation
     *
     * @return \App\Entity\Dataset
     */
    public function getDatasetAssociation()
    {
        return $this->dataset_association;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     * @return TempAccessKey
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Get uuid
     *
     * @return string 
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set generated
     *
     * @param string $generated
     * @return TempAccessKey
     */
    public function setGenerated($generated)
    {
        $this->generated = $generated;
        return $this;
    }

    /**
     * Get generated
     *
     * @return string 
     */
    public function getGenerated()
    {
        return $this->generated;
    }

    /**
     * Get first_access
     *
     * @return string 
     */
    public function getFirstAccess()
    {
        return $this->first_access;
    }

    /**
     * Set first_access
     *
     * @param string $first_access
     * @return TempAccessKey
     */
    public function setFirstAccess($first_access)
    {
        $this->first_access = $first_access;
        return $this;
    }

    /**
     * Find if link is exired
     *
     * @param string $first_access
     * @return boolean
     */
		public function isValid() {
		
			$tak_ttl="PT72H";
			if ($this->container->hasParameter('tak_ttl')) {
				$tak_ttl=$this->container->getParameter('tak_ttl');
			}					
			if (new \DateTime()<$tak->getFirstAccess()->add(new \DateInterval($tak_ttl))) {
				return true;
			}
			
			return false;
			
		}			


 }
