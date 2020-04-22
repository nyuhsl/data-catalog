<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Related resources such as code on Github 
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
 * @ORM\Table(name="other_resources")
 */
class OtherResource {
  /**
   * @ORM\Column(type="integer",name="other_resource_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $resource_name;

  /**
   * @ORM\Column(type="string",length=1028,nullable=true)
   */
  protected $resource_description;

  /**
   * @ORM\Column(type="string",length=1028)
   */
  protected $resource_url;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="other_resources")
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
    return $this->resource_name;
  }

    /**
     * Set datasets_dataset_uid
     *
     * @param \App\Entity\Dataset $datasetsDatasetUid
     * @return OtherResource
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
     * Set resource_name
     *
     * @param string $resourceName
     * @return OtherResource
     */
    public function setResourceName($resourceName)
    {
        $this->resource_name = $resourceName;

        return $this;
    }

    /**
     * Get resource_name
     *
     * @return string 
     */
    public function getResourceName()
    {
        return $this->resource_name;
    }

    /**
     * Set resource_description
     *
     * @param string $resourceDescription
     * @return OtherResource
     */
    public function setResourceDescription($resourceDescription)
    {
        $this->resource_description = $resourceDescription;

        return $this;
    }

    /**
     * Get resource_description
     *
     * @return string 
     */
    public function getResourceDescription()
    {
        return $this->resource_description;
    }

    /**
     * Set resource_url
     *
     * @param string $resourceUrl
     * @return OtherResource
     */
    public function setResourceUrl($resourceUrl)
    {
        $this->resource_url = $resourceUrl;

        return $this;
    }

    /**
     * Get resource_url
     *
     * @return string 
     */
    public function getResourceUrl()
    {
        return $this->resource_url;
    }

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
      return array(
        'resource_name'=>$this->resource_name,
        'resource_description'=>$this->resource_description,
        'resource_url'=>$this->resource_url
      );
    }
}
