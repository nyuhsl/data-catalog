<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * The different types of publishers (i.e. government, educational, etc.)
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
