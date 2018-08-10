<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Entity describing the measurement standards used in a dataset
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
 * @ORM\Table(name="measurement_standards")
 * @UniqueEntity("measurement_standard_name")
 */
class DataCollectionStandard {
  /**
   * @ORM\Column(type="integer",name="standard_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $measurement_standard_name;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string", length=256)
   */
  protected $measurement_standard_authority;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="data_collection_standards")
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
    return $this->measurement_standard_name;
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
     * Set measurement_standard_name
     *
     * @param string $measurementStandardName
     * @return DataCollectionStandard
     */
    public function setMeasurementStandardName($measurementStandardName)
    {
        $this->measurement_standard_name = $measurementStandardName;

        return $this;
    }

    /**
     * Get measurement_standard_name
     *
     * @return string 
     */
    public function getMeasurementStandardName()
    {
        return $this->measurement_standard_name;
    }

    /**
     * Set measurement_standard_authority
     *
     * @param string $measurementStandardAuthority
     * @return DataCollectionStandard
     */
    public function setMeasurementStandardAuthority($measurementStandardAuthority)
    {
        $this->measurement_standard_authority = $measurementStandardAuthority;

        return $this;
    }

    /**
     * Get measurement_standard_authority
     *
     * @return string 
     */
    public function getMeasurementStandardAuthority()
    {
        return $this->measurement_standard_authority;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return DataCollectionStandard
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
     * @param \AppBundle\Entity\Dataset $datasets
     * @return DataCollectionStandard
     */
    public function addDataset(\AppBundle\Entity\Dataset $datasets)
    {
        $this->datasets[] = $datasets;

        return $this;
    }

    /**
     * Remove datasets
     *
     * @param \AppBundle\Entity\Dataset $datasets
     */
    public function removeDataset(\AppBundle\Entity\Dataset $datasets)
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
     * Get all properties
     *
     * @return array
     **/
    public function getAllProperties() {
      return array(
        'measurement_standard_name' => $this->measurement_standard_name,
        'measurement_standard_authority' => $this->measurement_standard_authority
      );
    }

}
