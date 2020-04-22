<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * The organization that published a dataset
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
 * @ORM\Table(name="publishers")
 * @UniqueEntity("publisher_name")
 */
class Publisher {
  /**
   * @ORM\Column(type="integer",name="publisher_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $publisher_name;

  /**
   * @ORM\Column(type="string",length=512)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string",length=1028, nullable=true)
   */
  protected $publisher_url;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="publishers")
   */
  protected $datasets;




    /**
     * Add datasets
     *
     * @param \App\Entity\Dataset $datasets
     * @return DataType
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
   * Get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->publisher_name;
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
     * Set publisher_name
     *
     * @param string $publisherName
     * @return Publisher
     */
    public function setPublisherName($publisherName)
    {
        $this->publisher_name = $publisherName;

        return $this;
    }

    /**
     * Get publisher_name
     *
     * @return string 
     */
    public function getPublisherName()
    {
        return $this->publisher_name;
    }

    /**
     * Set publisher_url
     *
     * @param string $publisherUrl
     * @return Publisher
     */
    public function setPublisherUrl($publisherUrl)
    {
        $this->publisher_url = $publisherUrl;

        return $this;
    }

    /**
     * Get publisher_url
     *
     * @return string 
     */
    public function getPublisherUrl()
    {
        return $this->publisher_url;
    }

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Publisher
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
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
        return array(
            'publisher_name'=>$this->publisher_name,
            'publisher_url'=>$this->publisher_url,
        );
    }
}
