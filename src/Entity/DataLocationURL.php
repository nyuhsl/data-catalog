<?php
// src/App/Entity/DataLocationURL.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
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
 * @ORM\Table(name="data_location_urls")
 */
class DataLocationURL {
  /**
   * @ORM\Column(type="integer",name="location_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=1028)
   */
  protected $data_access_url;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="data_location_urls")
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
}
