<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Software used to produce or analyze the dataset
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
 * @ORM\Table(name="related_software")
 * @UniqueEntity("software_name")
 */
class RelatedSoftware {
  /**
   * @ORM\Column(type="integer",name="related_software_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128, unique=true)
   */
  protected $software_name;

  /**
   * @ORM\Column(type="string",length=512, unique=false, nullable=true)
   */
  protected $software_description;

  /**
   * @ORM\Column(type="string",length=512, unique=false, nullable=true)
   */
  protected $software_url;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="related_software")
   **/
  protected $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->software_name;
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
     * Set software_name
     *
     * @param string $software_name
     * @return RelatedSoftware
     */
    public function setSoftwareName($software_name)
    {
        $this->software_name = $software_name;

        return $this;
    }

    /**
     * Get software_name
     *
     * @return string 
     */
    public function getSoftwareName()
    {
        return $this->software_name;
    }

    /**
     * Set software_description
     *
     * @param string $software_description
     * @return RelatedSoftware
     */
    public function setSoftwareDescription($software_description)
    {
        $this->software_description = $software_description;

        return $this;
    }

    /**
     * Get software_description
     *
     * @return string 
     */
    public function getSoftwareDescription()
    {
        return $this->software_description;
    }

    /**
     * Set software_url
     *
     * @param string $software_url
     * @return RelatedSoftware
     */
    public function setSoftwareUrl($software_url)
    {
        $this->software_url = $software_url;

        return $this;
    }

    /**
     * Get software_url
     *
     * @return string 
     */
    public function getSoftwareUrl()
    {
        return $this->software_url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return RelatedSoftware
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
     * @return RelatedSoftware
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
            'software_name'=>$this->software_name,
            'software_description'=>$this->software_description,
            'software_url'=>$this->software_url
        );
    }

}
