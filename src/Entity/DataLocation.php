<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * The location of the dataset on the web, or a person who can provide access
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
 * @ORM\Table(name="data_locations")
 */
class DataLocation {
  /**
   * @ORM\Column(type="integer",name="location_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $data_location;

  /**
   * @ORM\Column(type="string",length=1028,nullable=true)
   */
  protected $location_content;

  /**
   * @ORM\Column(type="string",length=1028)
   */
  protected $data_access_url;


  /**
   * @ORM\Column(type="string", length=512, nullable=true)
   */
  protected $accession_number;


  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="data_locations")
   * @ORM\JoinColumn(name="datasets_dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $datasets_dataset_uid;


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
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->data_access_url;
  }


    /**
     * Set data_access_url
     *
     * @param string $dataAccessUrl
     * @return DataLocationURL
     */
    public function setDataAccessUrl($dataAccessUrl)
    {
        $this->data_access_url = $dataAccessUrl;

        return $this;
    }

    /**
     * Get data_access_url
     *
     * @return string 
     */
    public function getDataAccessUrl()
    {
        return $this->data_access_url;
    }

    /**
     * Set accession_number
     *
     * @param string $accessionNumber
     * @return Dataset
     */
    public function setAccessionNumber($accessionNumber)
    {
        $this->accession_number = $accessionNumber;

        return $this;
    }

    /**
     * Get accession_number
     *
     * @return string 
     */
    public function getAccessionNumber()
    {
        return $this->accession_number;
    }

    /**
     * Set datasets_dataset_uid
     *
     * @param \App\Entity\Dataset $datasetsDatasetUid
     * @return DataLocationURL
     */
    public function setDatasetsDatasetUid(\App\Entity\Dataset $datasetsDatasetUid = null)
    {
        $this->datasets_dataset_uid = $datasetsDatasetUid;

        return $this;
    }

    /**
     * Get datasets_dataset_uid
     *
     * @return \App\Entity\Dataset 
     */
    public function getDatasetsDatasetUid()
    {
        return $this->datasets_dataset_uid;
    }

    /**
     * Set data_location
     *
     * @param string $dataLocation
     * @return DataLocation
     */
    public function setDataLocation($dataLocation)
    {
        $this->data_location = $dataLocation;

        return $this;
    }

    /**
     * Get data_location
     *
     * @return string 
     */
    public function getDataLocation()
    {
        return $this->data_location;
    }

    /**
     * Set location_content
     *
     * @param string $locationContent
     * @return DataLocation
     */
    public function setLocationContent($locationContent)
    {
        $this->location_content = $locationContent;

        return $this;
    }

    /**
     * Get location_content
     *
     * @return string 
     */
    public function getLocationContent()
    {
        return $this->location_content;
    }

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
      return array(
        'data_location'=>$this->data_location,
        'location_content'=>$this->location_content,
        'data_access_url'=>$this->data_access_url,
        'accession_number'=>$this->accession_number
      );
    }
}
