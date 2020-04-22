<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Alternate titles of a dataset
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
 * @ORM\Table(name="dataset_alternate_titles")
 */
class DatasetAlternateTitle {
  /**
   * @ORM\Column(type="integer",name="alternate_title_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $alt_title;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="dataset_alternate_titles")
   * @ORM\JoinColumn(name="datasets_dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $datasets_dataset_uid;


  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->alt_title;
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
     * Set alt_title
     *
     * @param string $altTitle
     * @return DatasetAlternateTitle
     */
    public function setAltTitle($altTitle)
    {
        $this->alt_title = $altTitle;

        return $this;
    }

    /**
     * Get alt_title
     *
     * @return string 
     */
    public function getAltTitle()
    {
        return $this->alt_title;
    }

    /**
     * Set datasets_dataset_uid
     *
     * @param \App\Entity\Dataset $datasetsDatasetUid
     * @return DatasetAlternateTitle
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
