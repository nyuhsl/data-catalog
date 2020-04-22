<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Describes geographic areas
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
 * @ORM\Table(name="subject_geographic_areas")
 * @UniqueEntity("geographic_area_name")
 */
class SubjectGeographicArea {
  /**
   * @ORM\Column(type="integer",name="area_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $geographic_area_name;

  /**
   * @ORM\Column(type="string",length=256, nullable=true)
   */
  protected $geographic_area_authority;


  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_geographic_areas")
   **/
  protected $datasets;

    public function __construct() {
      $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

  /**
   * Get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->geographic_area_name;
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
     * Set geographic_area_name
     *
     * @param string $geographicAreaName
     * @return SubjectGeographicArea
     */
    public function setGeographicAreaName($geographicAreaName)
    {
        $this->geographic_area_name = $geographicAreaName;

        return $this;
    }

    /**
     * Get geographic_area_name
     *
     * @return string 
     */
    public function getGeographicAreaName()
    {
        return $this->geographic_area_name;
    }

    /**
     * Set geographic_area_authority
     *
     * @param string $geographicAreaAuthority
     * @return SubjectGeographicArea
     */
    public function setGeographicAreaAuthority($geographicAreaAuthority)
    {
        $this->geographic_area_authority = $geographicAreaAuthority;

        return $this;
    }

    /**
     * Get geographic_area_authority
     *
     * @return string 
     */
    public function getGeographicAreaAuthority()
    {
        return $this->geographic_area_authority;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectGeographicArea
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

    /**
     * Add datasets
     *
     * @param \App\Entity\Dataset $datasets
     * @return SubjectGeographicArea
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
            'geographic_area_name'=>$this->geographic_area_name,
            'geographic_area_authority'=>$this->geographic_area_authority
        );
    }
}
