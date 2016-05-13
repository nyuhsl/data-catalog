<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * The age of the studied population
 *
 * @ORM\Entity
 * @ORM\Table(name="subject_population_ages")
 * @UniqueEntity("age_group")
 */
class SubjectPopulationAge {
  /**
   * @ORM\Column(type="integer",name="pop_age_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $age_group;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_population_ages")
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
    return $this->age_group;
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
     * Set age_group
     *
     * @param string $ageGroup
     * @return SubjectPopulationAge
     */
    public function setAgeGroup($ageGroup)
    {
        $this->age_group = $ageGroup;

        return $this;
    }

    /**
     * Get age_group
     *
     * @return string 
     */
    public function getAgeGroup()
    {
        return $this->age_group;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectPopulationAge
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
     * @return SubjectPopulationAge
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
