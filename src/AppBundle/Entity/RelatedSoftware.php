<?php
namespace AppBundle\Entity;

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
 * @UniqueEntity("related_software")
 */
class RelatedSoftware {
  /**
   * @ORM\Column(type="integer",name="related_software_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $related_software;

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
    return $this->related_software;
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
     * Set related_software
     *
     * @param string $related_software
     * @return RelatedSoftware
     */
    public function setRelatedSoftware($related_software)
    {
        $this->related_software = $related_software;

        return $this;
    }

    /**
     * Get related_software
     *
     * @return string 
     */
    public function getRelatedSoftware()
    {
        return $this->related_software;
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
     * @param \AppBundle\Entity\Dataset $datasets
     * @return RelatedSoftware
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
}
