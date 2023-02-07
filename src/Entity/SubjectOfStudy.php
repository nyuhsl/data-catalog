<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * The subject of study covered by this dataset
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
 * @ORM\Table(name="subject_of_study")
 */
class SubjectOfStudy {
  /**
   * @ORM\Column(type="integer",name="subject_of_study_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=512, unique=false, nullable=false)
   */
  protected $common_name;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $species;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $strain;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $tissue;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $cell_line;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_of_study")
   **/
  protected $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
      $displayName = $this->common_name;
      if (isset($this->species)) {
         $displayName .= " / " . $this->species;
      }
      if (isset($this->strain)) {
         $displayName .= " / " . $this->strain;
      }
      if (isset($this->tissue)) {
         $displayName .= " / " . $this->tissue;
      }
      if (isset($this->cell_line)) {
         $displayName .= " / " . $this->cell_line;
      }

      return $displayName;
  }

  /**
   * get name for display in dataset record
   * displays the most specific field we have on the entity
   *
   * @return string
   */
  public function getDisplayForDatasetRecord() {
      if (isset($this->cell_line)) {
         return $this->cell_line;
      }
      if (isset($this->tissue)) {
         return $this->tissue;
      }
      if (isset($this->strain)) {
         return $this->strain;
      }
      if (isset($this->species)) {
         return $this->species;
      }
      if (isset($this->common_name)) {
         return $this->common_name;
      }
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
     * Set common_name
     *
     * @param string $common_name
     * @return SubjectOfStudy
     */
    public function setCommonName($common_name)
    {
        $this->common_name = $common_name;

        return $this;
    }

    /**
     * Get common_name
     *
     * @return string 
     */
    public function getCommonName()
    {
        return $this->common_name;
    }

    /**
     * Set strain
     *
     * @param string $strain
     * @return SubjectOfStudy
     */
    public function setStrain($strain)
    {
        $this->strain = $strain;

        return $this;
    }

    /**
     * Get strain
     *
     * @return string 
     */
    public function getStrain()
    {
        return $this->strain;
    }

    /**
     * Set species
     *
     * @param string $species
     * @return SubjectOfStudy
     */
    public function setSpecies($species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return string 
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set tissue
     *
     * @param string $tissue
     * @return SubjectOfStudy
     */
    public function setTissue($tissue)
    {
        $this->tissue = $tissue;

        return $this;
    }

    /**
     * Get tissue
     *
     * @return string 
     */
    public function getTissue()
    {
        return $this->tissue;
    }

    /**
     * Set cell_line
     *
     * @param string $cell_line
     * @return SubjectOfStudy
     */
    public function setCellLine($cell_line)
    {
        $this->cell_line = $cell_line;

        return $this;
    }

    /**
     * Get cell_line
     *
     * @return string 
     */
    public function getCellLine()
    {
        return $this->cell_line;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectOfStudy
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
     * @return SubjectOfStudy
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
            'common_name'=>$this->common_name,
            'species'=>$this->species,
            'strain'=>$this->strain,
            'tissue'=>$this->tissue,
            'cell_line'=>$this->cell_line
        );
    }
}
