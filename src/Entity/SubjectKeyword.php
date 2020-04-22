<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Entity describing keywords used to tag datasets
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
 * @ORM\Table(name="subject_keywords")
 * @UniqueEntity("keyword")
 */
class SubjectKeyword {
  /**
   * @ORM\Column(type="integer",name="keyword_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $keyword;

  /**
   * @ORM\Column(type="string",length=256, nullable=true)
   */

  protected $mesh_code;

  /**
   * @ORM\Column(type="string",length=256)
   */

  protected $slug;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_keywords")
   **/
  protected $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->keyword;
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
     * Set keyword
     *
     * @param string $keyword
     * @return SubjectKeyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set mesh_code
     *
     * @param string $meshCode
     * @return SubjectKeyword
     */
    public function setMeshCode($meshCode)
    {
        $this->mesh_code = $meshCode;

        return $this;
    }

    /**
     * Get mesh_code
     *
     * @return string 
     */
    public function getMeshCode()
    {
        return $this->mesh_code;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectKeyword
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
     * @return SubjectKeyword
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
        'keyword'=>$this->keyword,
        'mesh_code'=>$this->mesh_code
      );
    }

}
