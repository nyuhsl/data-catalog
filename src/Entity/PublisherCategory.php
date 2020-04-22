<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * The different types of publishers (i.e. government, educational, etc.)
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
 * @ORM\Table(name="publisher_categories")
 * @UniqueEntity("publisher_category")
 */
class PublisherCategory {
  /**
   * @ORM\Column(type="integer", name="publisher_category_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $publisher_category;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Publisher", mappedBy="publisher_categories")
   */
  protected $publishers;

    /**
     * Add publisher
     *
     * @param \App\Entity\Publisher $publisher
     * @return PublisherCategory
     */
    public function addPublisher(\App\Entity\Publisher $publisher)
    {
        $this->publishers[] = $publisher;

        return $this;
    }

    /**
     * Remove publisher
     *
     * @param \App\Entity\Publisher $publisher
     */
    public function removePublisher(\App\Entity\Publisher $publisher)
    {
        $this->publishers->removeElement($publisher);
    }

    /**
     * Get publishers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->publisher_category;
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
     * Set publisher_category
     *
     * @param string $publisherCategory
     * @return PublisherCategory
     */
    public function setPublisherCategory($publisherCategory)
    {
        $this->publisher_category = $publisherCategory;

        return $this;
    }

    /**
     * Get publisher_category
     *
     * @return string 
     */
    public function getPublisherCategory()
    {
        return $this->publisher_category;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return PublisherCategory
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
}
