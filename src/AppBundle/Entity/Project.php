<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Entity describing related projects associated with a dataset
 *
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
 * @ORM\Table(name="project")
 * @UniqueEntity("project_name")
 */
class Project {
  /**
   * @ORM\Column(type="integer",name="project_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;


  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $project_name;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string",length=1028, nullable=true)
   */
  protected $project_description;


  /**
   * @ORM\Column(type="string",length=1028, nullable=true)
   */
  protected $project_url;
    

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="projects")
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
     return $this->project_name;
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
     * Set project name
     *
     * @param string $projectName
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->project_name = $projectName;

        return $this;
    }

    /**
     * Get project
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * Set project_description
     *
     * @param string $projectDescription
     * @return Project
     */
    public function setProjectDescription($projectDescription)
    {
        $this->project_description = $projectDescription;

        return $this;
    }

    /**
     * Get project_description
     *
     * @return string 
     */
    public function getProjectDescription()
    {
        return $this->project_description;
    }

    /**
     * Set project_url
     *
     * @param string $projectUrl
     * @return Project
     */
    public function setProjectUrl($projectUrl)
    {
        $this->project_url = $projectUrl;

        return $this;
    }

    /**
     * Get project_url
     *
     * @return string 
     */
    public function getProjectUrl()
    {
        return $this->project_url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
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
     * @param \AppBundle\Entity\Dataset $datasets
     * @return Project
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


    /** 
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties()
    {
        return array(
            'project_name' => $this->project_name,
            'project_description' => $this->project_description,
            'project_url' => $this->project_url,
        );
    }
}
