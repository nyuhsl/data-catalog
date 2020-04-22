<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * An entity describing subject sex
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
 * @ORM\Table(name="subject_sexes")
 */
class SubjectSex {
  /**
   * @ORM\Column(type="integer",name="sex_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $subject_sex;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $slug;
    
  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_sexes")
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
    return $this->subject_sex;
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
     * Set subject_sex
     *
     * @param string $subjectSex
     * @return SubjectSex
     */
    public function setSubjectSex($subjectSex)
    {
        $this->subject_sex = $subjectSex;

        return $this;
    }

    /**
     * Get subject_sex
     *
     * @return string 
     */
    public function getSubjectSex()
    {
        return $this->subject_sex;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectSex
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
     * Add datasets
     *
     * @param \App\Entity\Dataset $datasets
     * @return SubjectSex
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
}
