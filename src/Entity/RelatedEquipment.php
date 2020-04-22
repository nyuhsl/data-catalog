<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * The equipment used to produce the data
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
 * @ORM\Table(name="related_equipment")
 * @UniqueEntity("related_equipment")
 */
class RelatedEquipment {
  /**
   * @ORM\Column(type="integer",name="related_equipment_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $related_equipment;

  /**
   * @ORM\Column(type="string",length=1028, unique=false, nullable=true)
   */
  protected $equipment_description;

  /**
   * @ORM\Column(type="string",length=1028, unique=false, nullable=true)
   */
  protected $equipment_url;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="related_equipment")
   **/
  protected $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->related_equipment;
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
     * Set related_equipment
     *
     * @param string $related_equipment
     * @return RelatedEquipment
     */
    public function setRelatedEquipment($related_equipment)
    {
        $this->related_equipment = $related_equipment;

        return $this;
    }

    /**
     * Get related_equipment
     *
     * @return string 
     */
    public function getRelatedEquipment()
    {
        return $this->related_equipment;
    }

    /**
     * Set equipment_description
     *
     * @param string $equipment_description
     * @return RelatedEquipment
     */
    public function setEquipmentDescription($equipment_description)
    {
        $this->equipment_description = $equipment_description;

        return $this;
    }

    /**
     * Get equipment_description
     *
     * @return string 
     */
    public function getEquipmentDescription()
    {
        return $this->equipment_description;
    }

    /**
     * Set equipment_url
     *
     * @param string $equipment_url
     * @return RelatedEquipment
     */
    public function setEquipmentUrl($equipment_url)
    {
        $this->equipment_url = $equipment_url;

        return $this;
    }

    /**
     * Get equipment_url
     *
     * @return string 
     */
    public function getEquipmentUrl()
    {
        return $this->equipment_url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return RelatedEquipment
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
 
    public function __construct() {
      $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add datasets
     *
     * @param \App\Entity\Dataset $datasets
     * @return RelatedEquipment
     */
    public function addDataset(\App\Entity\Dataset $datasets)
    {
        $this->datasets[] = $datasets;

        return $this;
    }

    /**
     * Remove datasets
     *
     * @param \App\Entity\Dataset $datasets
     */
    public function removeDataset(\App\Entity\Dataset $datasets)
    {
        $this->datasets->removeElement($datasets);
    }

    /**
     * Get datasets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatasets()
    {
        return $this->datasets;
    }

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
        return array(
            'related_equipment'=>$this->related_equipment,
            'equipment_description'=>$this->equipment_description,
            'equipment_url'=>$this->equipment_url,
        );
    }

}
