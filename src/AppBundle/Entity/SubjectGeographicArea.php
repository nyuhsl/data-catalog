<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Describes geographic areas
 *
 * @ORM\Entity
 * @ORM\Table(name="subject_geographic_areas")
 * @UniqueEntity("geographic_area_name")
 */
class SubjectGeographicArea {
  /**
   * @ORM\Column(type="integer",name="area_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $geographic_area_name;

  /**
   * @ORM\Column(type="string",length=256, nullable=true)
   */
  protected $geographic_area_authority;


  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_geographic_areas")
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
    return $this->geographic_area_name;
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
     * Set geographic_area_name
     *
     * @param string $geographicAreaName
     * @return SubjectGeographicArea
     */
    public function setGeographicAreaName($geographicAreaName)
    {
        $this->geographic_area_name = $geographicAreaName;

        return $this;
    }

    /**
     * Get geographic_area_name
     *
     * @return string 
     */
    public function getGeographicAreaName()
    {
        return $this->geographic_area_name;
    }

    /**
     * Set geographic_area_authority
     *
     * @param string $geographicAreaAuthority
     * @return SubjectGeographicArea
     */
    public function setGeographicAreaAuthority($geographicAreaAuthority)
    {
        $this->geographic_area_authority = $geographicAreaAuthority;

        return $this;
    }

    /**
     * Get geographic_area_authority
     *
     * @return string 
     */
    public function getGeographicAreaAuthority()
    {
        return $this->geographic_area_authority;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectGeographicArea
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
     * @return SubjectGeographicArea
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
