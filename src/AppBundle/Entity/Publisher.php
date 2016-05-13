<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * The organization that published a dataset
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
   * @ORM\ManyToMany(targetEntity="PublisherCategory", cascade={"all"})
   * @ORM\JoinTable(name="publishers_publisher_categories",
   *                schema="publishers_publisher_categories",
   *                joinColumns={@ORM\JoinColumn(name="publisher_id",
   *                            referencedColumnName="publisher_id")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="publisher_category_id",
   *                            referencedColumnName="publisher_category_id")}
   *               )
   */
  protected $publisher_categories;


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
     * Add publisher_categories
     *
     * @param \AppBundle\Entity\PublisherCategory $publisherCategories
     * @return Publisher
     */
    public function addPublisherCategory(\AppBundle\Entity\PublisherCategory $publisherCategories)
    {
        $this->publisher_categories[] = $publisherCategories;

        return $this;
    }

    /**
     * Remove publisher_categories
     *
     * @param \AppBundle\Entity\PublisherCategory $publisherCategories
     */
    public function removePublisherCategory(\AppBundle\Entity\PublisherCategory $publisherCategories)
    {
        $this->publisher_categories->removeElement($publisherCategories);
    }

    /**
     * Get publisher_categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublisherCategories()
    {
        return $this->publisher_categories;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publisher_categories = new \Doctrine\Common\Collections\ArrayCollection();
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
}
