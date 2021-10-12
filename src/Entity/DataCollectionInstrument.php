<?php
namespace App\Entity;

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
 * **********
 * NOTE: For ease of upgrading, database tables will retain their old names for now
 * **********
 *
 * @ORM\Entity
 * @ORM\Table(name="measurement_standards")
 * @UniqueEntity("data_collection_instrument_name")
 */
class DataCollectionInstrument {
  /**
   * @ORM\Column(type="integer",name="standard_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $data_collection_instrument_name;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $url;

  /**
   * @ORM\Column(type="string", length=1026, nullable=true)
   */
  protected $notes;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="data_collection_instruments")
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
    return $this->data_collection_instrument_name;
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
     * Set data collection instrument name
     *
     * @param string $dataCollectionInstrumentName
     * @return DataCollectionInstrument
     */
    public function setDataCollectionInstrumentName($dataCollectionInstrumentName)
    {
        $this->data_collection_instrument_name = $dataCollectionInstrumentName;

        return $this;
    }

    /**
     * Get data collection instrument name
     *
     * @return string 
     */
    public function getDataCollectionInstrumentName()
    {
        return $this->data_collection_instrument_name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return DataCollectionInstrument
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return DataCollectionInstrument
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
     * Set notes
     *
     * @param string $notes
     * @return DataCollectionInstrument
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add datasets
     *
     * @param \App\Entity\Dataset $datasets
     * @return DataCollectionInstrument
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
     * Get all properties
     *
     * @return array
     **/
    public function getAllProperties() {
      return array(
        'data_collection_instrument_name' => $this->data_collection_instrument_name,
        'url' => $this->url,
        'notes' => $this->notes
      );
    }

}
